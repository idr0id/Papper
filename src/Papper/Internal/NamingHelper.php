<?php

namespace Papper\Internal;

class NamingHelper
{
	public static function possibleName($memberName, array $prefixes)
	{
		$names = self::possibleNames($memberName, $prefixes);
		return reset($names) ?: null;
	}

	public static function possibleNames($memberName, array $prefixes)
	{
		if (empty($memberName)) {
			return array();
		}

		$possibleNames = array($memberName);
		foreach ($prefixes as $prefix) {
			if (stripos($memberName, $prefix) === 0) {
				$withoutPrefix = substr($memberName, strlen($prefix));
				$possibleNames[] = $withoutPrefix;
			}
		}
		return $possibleNames;
	}
} 
