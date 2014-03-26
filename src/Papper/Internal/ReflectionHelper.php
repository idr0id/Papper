<?php

namespace Papper\Internal;

class ReflectionHelper
{
	/**
	 * @param \ReflectionClass $reflector
	 * @param int $argumentsCount
	 * @return \ReflectionMethod[]
	 */
	public static function getPublicMethods(\ReflectionClass $reflector, $argumentsCount)
	{
		$filter = function (\ReflectionMethod $method) use ($argumentsCount) {
			return $method->getNumberOfRequiredParameters() === $argumentsCount;
		};

		return array_filter($reflector->getMethods(\ReflectionMethod::IS_PUBLIC), $filter);
	}
}
