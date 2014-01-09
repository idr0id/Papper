<?php

namespace Papper;

use ReflectionProperty;

class Reflector
{
	private $reflector;
	/**
	 * @var ReflectorMember[]
	 */
	private $publicSetters = array();
	/**
	 * @var ReflectorMember[]
	 */
	private $publicGetters = array();

	public function __construct(\ReflectionClass $reflector)
	{
		$this->reflector = $reflector;
		$this->disassemble();
	}

	public function getName()
	{
		return $this->reflector->getName();
	}

	public function isInstance($object)
	{
		return $this->reflector->isInstance($object);
	}

	public function create()
	{
		return $this->reflector->newInstance();
	}

	public function hasPublicGetter($name)
	{
		return isset($this->publicGetters[$name]);
	}

	public function getPublicGetter($name)
	{
		return $this->publicGetters[$name];
	}

	public function getPublicSetters()
	{
		return $this->publicSetters;
	}

	private function disassemble()
	{
		// collect properties
		foreach ($this->reflector->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$this->publicGetters[$property->getName()] =
			$this->publicSetters[$property->getName()] = new ReflectorMember($property);
		}
		// collect methods
		foreach ($this->reflector->getMethods() as $method) {
			if (strpos($method->getName(), 'get') === 0) {
				$this->publicGetters[lcfirst(substr($method->getName(), 3))] = new ReflectorMember($method);
			}
			else if (strpos($method->getName(), 'set') === 0) {
				$this->publicSetters[lcfirst(substr($method->getName(), 3))] = new ReflectorMember($method);
			}
		}
	}
}
