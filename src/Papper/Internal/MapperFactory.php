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
			$mapping .= $this->render($mappingTemplate, array(
				'{{GetValue}}' => $this->render($propertyMap->getSourceGetter()->createNativeCodeTemplate(), array(
					'{{PropertyMap}}' => '$this->propertyMaps["' . $index . '"]',
				)),
				'{{ValueConverter}}' => $propertyMap->hasValueConverter() ? '$value = $this->propertyMaps["' . $index . '"]->getValueConverter()->convert($value)' : '',
				'{{SetValue}}' => $this->render($propertyMap->getDestinationSetter()->createNativeCodeTemplate(), array(
					'{{PropertyMap}}' => '$this->propertyMaps["' . $index . '"]',
				)),
				'{{PropertyMap}}' => '$this->propertyMaps["' . $index . '"]',
				'{{FromName}}' => $propertyMap->getSourceGetter()->getName(),
				'{{ToName}}' => $propertyMap->getDestinationSetter()->getName(),
			));
		}

		return $this->render($template, array(
			'{{ClassName}}' => $this->generateClassName($typeMap),
			'{{BeforeMapFunc}}' => $typeMap->hasBeforeMapFunc() ? 'call_user_func($this->beforeMapFunc, array($source, $destination))' : '',
			'{{Mapping}}' => $mapping,
			'{{AfterMapFunc}}' => $typeMap->hasAfterMapFunc() ? 'call_user_func($this->beforeMapFunc, array($source, $destination))' : '',
			'{{SourceType}}' => $typeMap->getSourceType(),
			'{{DestinationType}}' => $typeMap->getDestinationType(),
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
			if ($value === null) {
				$value = {{PropertyMap}}->getNullSubtitute();
			}
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
