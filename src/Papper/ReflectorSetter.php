<?php

namespace Papper;

use ReflectionProperty;
use \Reflector as PhpReflector;

class ReflectorSetter
{
	private $reflector;

	public function __construct(PhpReflector $reflector)
	{
		$this->reflector = $reflector;
	}

	public function set($object, $value)
	{
		if ($this->reflector instanceof ReflectionProperty) {
			$this->reflector->setValue($object, $value);
		} else {
			$this->reflector->invokeArgs($object, array($value));
		}
	}
}
