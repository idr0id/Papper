<?php

namespace Papper;

/**
 * Trait to get a fully qualified name of class using static method ::className().
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 * @see http://ru2.php.net/manual/ru/language.oop5.basic.php#language.oop5.basic.class.class
 */
trait ClassName
{
	public static function className()
	{
		return get_called_class();
	}
}
