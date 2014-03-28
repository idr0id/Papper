<?php

namespace Papper;

use Papper\Internal\Configuration;
use Papper\Internal\MappingExpression;

/**
 * Papper mapping engine
 *
 * @todo mapping options api
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class Engine
{
	/**
	 * @var Internal\Configuration
	 */
	private $config;

	public function __construct()
	{
		$this->config = new Configuration();
	}

	/**
	 * Returns current configuration
	 *
	 * @return Configuration
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Creates a TypeMap for the source's type and destinationType.
	 * Useful for creating TypeMaps for generic source data structures.
	 *
	 * @param string $sourceType
	 * @param string $destinationType
	 * @return MappingExpressionInterface
	 */
	public function createTypeMap($sourceType, $destinationType)
	{
		return new MappingExpression($this->config->findTypeMap($sourceType, $destinationType));
	}

	/**
	 * Maps source to an instance of destinationType. Mapping is performed according
	 * to the corresponding TypeMap. If no TypeMap exists for source.getClass()
	 * destinationType then one is created.
	 *
	 * @param object|object[] $source
	 * @param string $destinationType
	 * @param string|null $sourceType
	 * @throws MappingException
	 * @return object|object[]
	 */
	public function map($source, $destinationType, $sourceType = null)
	{
		if (is_array($source)) {
			if (empty($sourceType)) {
				throw new MappingException('Source type must specified explicitly for array mapping');
			}
		} else if (is_object($source)) {
			$sourceType = $sourceType ?: get_class($source);
		} else {
			throw new MappingException('Source type must be object instead of ' . gettype($source));
		}

		$typeMap = $this->config->findTypeMap($sourceType, $destinationType);

		try {
			$typeMap->validate();
			return $this->performMapping($typeMap, $source);
		} catch (\Exception $e) {
			throw new MappingException(
				sprintf("Error while mapping <%s:%s>", $typeMap->getSourceType(), $typeMap->getDestinationType()), 0, $e
			);
		}
	}

	/**
	 * Validates that every top level destination property is mapped to source property.
	 * If not, a ConfigurationException is thrown detailing any missing mappings.
	 *
	 * @throws ValidationException if any TypeMaps contain unmapped properties
	 */
	public function validate()
	{
		$errors = array();
		foreach ($this->config->getAllTypeMaps() as $typeMap) {
			try {
				$typeMap->validate();
			} catch (ValidationException $e) {
				$errors[] = $e->getMessage();
			}
		}

		if (!empty($errors)) {
			throw new ValidationException(implode("\n\n", $errors));
		}
	}

	private function performMapping(TypeMap $typeMap, $source)
	{
		$objectCreator = $typeMap->getObjectCreator();
		/** @var $propertyMaps PropertyMap[] */
		$propertyMaps = array_filter($typeMap->getPropertyMaps(), function(PropertyMap $propertyMap) {
			return !$propertyMap->isIgnored();
		});
		$destinationType = $typeMap->getDestinationType();

		$mapFunc = function ($source) use ($objectCreator, $propertyMaps, $destinationType) {
			$destination = $objectCreator->create($source);

			if (!$destination instanceof $destinationType) {
				throw new ValidationException(
					sprintf('Constucted object type expected <%s>, but actual <%s>', $destinationType, get_class($destination))
				);
			}

			foreach ($propertyMaps as $propertyMap) {
				$value = $propertyMap->getSourceGetter()->getValue($source);
				if ($propertyMap->hasValueConverter()) {
					$value = $propertyMap->getValueConverter()->converter($value);
				}
				$propertyMap->getDestinationSetter()->setValue($destination, $value);
			}
			return $destination;
		};

		if (is_array($source)) {
			return array_map($mapFunc, $source);
		} else {
			return $mapFunc($source);
		}
	}
}
