<?php

namespace Papper;

use Papper\Internal\Configuration;
use Papper\Internal\ExecuteMappingFluentSyntax;
use Papper\Internal\MappingFluentSyntax;

/**
 * Papper mapping engine
 *
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
	 * Returns mapping options
	 *
	 * @return MappingOptionsInterface
	 */
	public function getMappingOptions()
	{
		return $this->config->getMappingOptions();
	}

	/**
	 * Creates a TypeMap for the source's type and destination's type.
	 *
	 * @param string $sourceType Source type
	 * @param string $destinationType Destination type
	 * @throws ClassNotFoundException
	 * @return MappingFluentSyntaxInterface
	 */
	public function createMap($sourceType, $destinationType)
	{
		return new MappingFluentSyntax($this->config->findTypeMap($sourceType, $destinationType));
	}

	/**
	 * Configure map
	 *
	 * @param MappingConfigurationInterface $configuration
	 */
	public function configureMap(MappingConfigurationInterface $configuration)
	{
		$map = $this->createMap($configuration->getSourceType(), $configuration->getDestinationType());
		$configuration->configure($map);
	}

	/**
	 * Initialize a mapping from the source object.
	 * The source type can be is inferred from the source object.
	 *
	 * @param object|object[] $source Source object or collection to map from
	 * @param string|null $sourceType Source object type
	 * @throws MappingException
	 * @return ExecuteMappingFluentSyntaxInterface
	 */
	public function map($source, $sourceType = null)
	{
		return new ExecuteMappingFluentSyntax($this, $source, $sourceType);
	}

	/**
	 * Execute a mapping using MappingContext
	 *
	 * @param MappingContext $context Mapping context
	 * @return object|object[] Mapped destination object or collection
	 * @throws MappingException
	 * @throws ClassNotFoundException
	 */
	public function execute(MappingContext $context)
	{
		$typeMap = $this->config->findTypeMap($context->getSourceType(), $context->getDestinationType());

		try {
			$typeMap->validate();

			$mapFunc = $typeMap->getMapFunc();

			return (is_array($context->getSource()))
				? array_map($mapFunc, $context->getSource())
				: $mapFunc($context->getSource(), $context->getDestination());
		} catch (\Exception $e) {
			$message = sprintf("Error while mapping <%s:%s>", $typeMap->getSourceType(), $typeMap->getDestinationType());
			throw new MappingException($message, 0, $e);
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
			} catch (\Exception $e) {
				$errors[] = $e->getMessage();
			}
		}

		if (!empty($errors)) {
			throw new ValidationException(implode("\n\n", $errors));
		}
	}

	/**
	 * Clear out all existing configuration
	 */
	public function reset()
	{
		$this->config = new Configuration();
	}
}
