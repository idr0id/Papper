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
	 * @return MappingFluentSyntaxInterface
	 */
	public static function createMap($sourceType, $destinationType)
	{
		return self::engine()->createMap($sourceType, $destinationType);
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
	public static function map($source, $sourceType = null)
	{
		return self::engine()->map($source, $sourceType);
	}

	/**
	 * Returns mapping options
	 *
	 * @return MappingOptionsInterface
	 */
	public static function mappingOptions()
	{
		return self::engine()->getMappingOptions();
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

	/**
	 * Clear out all existing configuration
	 */
	public static function reset()
	{
		self::engine()->reset();
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
