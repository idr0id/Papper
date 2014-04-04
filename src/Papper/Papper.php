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
	 * Creates a TypeMap for the source's type and destination's type.
	 *
	 * @param string $sourceType Source type
	 * @param string $destinationType Destination type
	 * @return MappingExpressionInterface
	 */
	public static function createMap($sourceType, $destinationType)
	{
		return self::engine()->createMap($sourceType, $destinationType);
	}

	/**
	 * Execute a mapping from the source object to a new destination object.
	 * The source type is inferred from the source object.
	 * If no Map exists then one is created.
	 *
	 * @param object $source Source object to map from
	 * @param string $destinationType Destination type to create
	 * @param string|null $sourceType Source object type
	 * @throws MappingException
	 * @return object|object[]
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
	public static function mappingOptions()
	{
		return self::engine()->getConfig()->getMappingOptions();
	}

	/**
	 * Validates that every top level destination property is mapped to source property.
	 * If not, a ValidationException is thrown detailing any missing mappings.
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
