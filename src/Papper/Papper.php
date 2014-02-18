<?php

namespace Papper;

class Papper
{
	public static function createMap($sourceClass, $destinationClass)
	{
		return static::context()->createMap($sourceClass, $destinationClass);
	}

	public static function map($sourceClass, $destinationClass, $source)
	{
		return static::context()->map($sourceClass, $destinationClass, $source);
	}

	private static function context()
	{
		static $context;
		return $context ?: $context = new Context();
	}
}
