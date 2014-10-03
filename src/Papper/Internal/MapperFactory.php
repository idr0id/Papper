<?php

namespace Papper\Internal;

use Papper\TypeMap;

class MapperFactory
{
	/**
	 * @var \SplObjectStorage
	 */
	private $mappers;
	/**
	 * @var bool
	 */
	private $isCaching;
	/**
	 * @var string
	 */
	private $cachePath;

	public function __construct()
	{
		$this->mappers = new \SplObjectStorage();
	}

	public function enableCaching($cachePath)
	{
		$this->isCaching = true;
		$this->cachePath = $cachePath;
	}

	public function disableCaching()
	{
		$this->isCaching = false;
	}

	public function create(TypeMap $typeMap)
	{
		if (!$this->mappers->contains($typeMap)) {
			$mapper = $this->isCaching ? $this->createNativeCodeMapper($typeMap) : $this->createSimpleMapper($typeMap);
			$this->mappers->attach($typeMap, $mapper);
		}
		return $this->mappers[$typeMap];
	}

	private function createSimpleMapper(TypeMap $typeMap)
	{
		return new Mapper($typeMap);
	}

	private function createNativeCodeMapper(TypeMap $typeMap)
	{
		$classname = $this->generateClassName($typeMap);
		$filename = $this->cachePath . DIRECTORY_SEPARATOR . $classname . '.php';
		if (!file_exists($filename)) {
			file_put_contents($filename, $this->compileNativeCodeMapper($typeMap));
		}
		require_once $filename;
		return new $classname($typeMap);
	}

	private function compileNativeCodeMapper(TypeMap $typeMap)
	{
		$template = $this->getMainTemplate();
		$mappingTemplate = $this->getMappingTemplate();
		$mapping = '';
		foreach ($typeMap->getPropertyMaps() as $index => $propertyMap) {
			$mappingTmp = $this->render($mappingTemplate, array(
				'{{GetValue}}' => $propertyMap->getSourceGetter()->createNativeCodeTemplate(),
				'{{ValueConverter}}' => $propertyMap->hasValueConverter() ? '$value = {{PropertyMap}}->getValueConverter()->convert($value)' : '',
				'{{SetValue}}' => $propertyMap->getDestinationSetter()->createNativeCodeTemplate(),
				'{{FromName}}' => $propertyMap->getSourceGetter()->getName(),
				'{{ToName}}' => $propertyMap->getDestinationSetter()->getName(),
				'{{NullSubtitute}}' => $propertyMap->getNullSubtitute() !== null ? 'if ($value === null) $value = {{PropertyMap}}->getNullSubtitute()' : '',
			));

			$mapping .= $this->render($mappingTmp, array('{{PropertyMap}}' => '$this->propertyMaps["' . $index . '"]'));
		}

		return $this->render($template, array(
			'{{ClassName}}' => $this->generateClassName($typeMap),
			'{{Mapping}}' => $mapping,
			'{{SourceType}}' => $typeMap->getSourceType(),
			'{{DestinationType}}' => $typeMap->getDestinationType(),
			'{{BeforeMapFunc}}' => $typeMap->hasBeforeMapFunc() ? 'call_user_func($this->beforeMapFunc, array($source, $destination))' : '',
			'{{AfterMapFunc}}' => $typeMap->hasAfterMapFunc() ? 'call_user_func($this->beforeMapFunc, array($source, $destination))' : '',
		));
	}

	private function render($template, array $placeholders)
	{
		return str_replace(array_keys($placeholders), array_values($placeholders), $template);
	}

	private function getMainTemplate()
	{
		return <<<'TEMPLATE'
<?php
class {{ClassName}} implements Papper\Internal\MapperInterface
{
	public function __construct(Papper\TypeMap $typeMap)
	{
		$this->objectCreator = $typeMap->getObjectCreator();
		$this->propertyMaps = $typeMap->getMappedPropertyMaps();
		$this->sourceType = $typeMap->getSourceType();
		$this->destinationType = $typeMap->getDestinationType();
		$this->beforeMapFunc = $typeMap->getBeforeMapFunc();
		$this->afterMapFunc = $typeMap->getAfterMapFunc();
	}

	public function map($source, $destination = null)
	{
		return $this->mapImpl($source, $destination);
	}

	private function mapImpl({{SourceType}} $source, {{DestinationType}} $destination = null)
	{
		if ($destination === null) {
			$destination = $this->objectCreator->create($source);
		}

		{{BeforeMapFunc}}
		{{Mapping}}
		{{AfterMapFunc}}

		return $destination;
	}
}
TEMPLATE;
	}

	private function getMappingTemplate()
	{
		return <<<'MAPPING'
		// {{FromName}} => {{ToName}}
		$value = {{GetValue}};
		{{ValueConverter}};
		{{NullSubtitute}};
		{{SetValue}};

MAPPING;
	}

	/**
	 * @param TypeMap $typeMap
	 * @return string
	 */
	private function generateClassName(TypeMap $typeMap)
	{
		return 'Mapper__' . str_replace('\\', '', $typeMap->getSourceType()) . '_' . str_replace('\\', '', $typeMap->getDestinationType());
	}
}
