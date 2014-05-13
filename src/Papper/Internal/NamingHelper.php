<?php

namespace Papper\Internal;

class NamingHelper
{
	public static function possibleName($memberName, array $prefixes)
	{
		if (empty($memberName)) {
			return null;
		}
		foreach ($prefixes as $prefix) {
			if (stripos($memberName, $prefix) === 0) {
				$withoutPrefix = substr($memberName, strlen($prefix));
				return $withoutPrefix;
			}
		}
		return null;
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
