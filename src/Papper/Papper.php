<?php

namespace Papper;

class Papper
{
	public static function createMap($sourceClass, $destinationClass)
	{
		return static::instance()->createMap($sourceClass, $destinationClass);
	}

	public static function map($sourceClass, $destinationClass, $source)
	{
		return static::instance()->map($sourceClass, $destinationClass, $source);
	}

	private static function instance()
	{
		static $instance;
		return $instance ?: $instance = new PapperImpl();
	}
}
