<?php

namespace Papper;

use ReflectionMethod;
use ReflectionProperty;

class ReflectorMember
{
	/**
	 * @var ReflectionProperty|ReflectionMethod
	 */
	protected $reflector;

	public function __construct($reflector)
	{
		$this->assertReflectorClass($reflector);
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

	public function setValue($object, $value)
	{
		if ($this->reflector instanceof ReflectionProperty) {
			$this->reflector->setValue($object, $value);
		} else {
			$this->reflector->invokeArgs($object, array($value));
		}
	}

	/**
	 * @param mixed $reflector
	 * @throws ReflectorException
	 */
	private function assertReflectorClass($reflector)
	{
		if (!$reflector instanceof ReflectionProperty && !$reflector instanceof ReflectionMethod) {
			throw new ReflectorException(sprintf("Reflector must be instance of ReflectionProperty or ReflectionMethod, not %s", get_class($reflector)));
		}
	}
} 
