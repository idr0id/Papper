<?php

namespace Papper;

/**
 * Main entry point for Papper, for both creating maps and performing maps.
 * Static proxy to Engine.
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class Papper
{
	/**
	 * Creates a TypeMap for the source's type and destinationType.
	 *
	 * @param string $sourceType
	 * @param string $destinationType
	 * @return MappingExpressionInterface
	 */
	public static function createMap($sourceType, $destinationType)
	{
		return self::engine()->createTypeMap($sourceType, $destinationType);
	}

	/**
	 * Maps source to an instance of destinationType. Mapping is performed according
	 * to the corresponding TypeMap. If no TypeMap exists for source.getClass()
	 * destinationType then one is created.
	 *
	 * @param object|object[] $source
	 * @param string $destinationType
	 * @param string|null $sourceType
	 * @return object
	 */
	public static function map($source, $destinationType, $sourceType = null)
	{
		return self::engine()->map($source, $destinationType, $sourceType);
	}

	/**
	 * Returns mapping options
	 *
	 * @return MappingOptionsInterface
	 */
	public function mappingOptions()
	{
		return self::engine()->getConfig()->getMappingOptions();
	}

	/**
	 * Validates that every top level destination property is mapped to source property.
	 * If not, a ConfigurationException is thrown detailing any missing mappings.
	 *
	 * @throws ValidationException if any TypeMaps contain unmapped properties
	 */
	public static function validate()
	{
		self::engine()->validate();
	}

	//<editor-fold desc="Singleton of Engine">
	/**
	 * Returns engine singleton instance
	 *
	 * @return Engine
	 */
	private static function engine()
	{
		static $instance;
		return $instance ?: $instance = new Engine();
	}

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function __wakeup()
	{
	}
	//</editor-fold>
}
