<?php

namespace Papper;

use \Reflector as PhpReflector;

class ReflectorGetter
{
	private $reflector;

	public function __construct(PhpReflector $reflector)
	{
		$this->reflector = $reflector;
	}

	public function getValue($object)
	{
		if ($this->reflector instanceof \ReflectionProperty) {
			return $this->reflector->getValue($object);
		} else {
			return $this->reflector->invoke($object);
		}

	}
}
