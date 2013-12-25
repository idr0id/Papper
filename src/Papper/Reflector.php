<?php

namespace Papper;

use ReflectionProperty;

class Reflector
{
	private $reflector;
	/**
	 * @var ReflectorSetter[]
	 */
	private $setters = array();
	/**
	 * @var ReflectorGetter[]
	 */
	private $getters = array();

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

	public function hasGetter($name)
	{
		return isset($this->getters[$name]);
	}

	public function getGetter($name)
	{
		return $this->getters[$name];
	}

	public function getSetters()
	{
		return $this->setters;
	}

	public function getGetters()
	{
		return $this->getters;

	}

	public function hasProperty($name)
	{
		return $this->reflector->hasProperty($name) && $this->reflector->getProperty($name)->isPublic();
	}

	public function hasMethod($name)
	{
		return $this->reflector->hasMethod($name) && $this->reflector->getMethod($name)->isPublic();
	}

	private function disassemble()
	{
		// collect properties
		foreach ($this->reflector->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$this->setters[$property->getName()] = new ReflectorSetter($property);
			$this->getters[$property->getName()] = new ReflectorGetter($property);
		}
		// collect methods
		foreach ($this->reflector->getMethods() as $method) {
			if (strpos($method->getName(), 'get') === 0) {
				$this->getters[lcfirst(substr($method->getName(), 3))] = new ReflectorGetter($method);
			}
			else if (strpos($method->getName(), 'set') === 0) {
				$this->setters[lcfirst(substr($method->getName(), 3))] = new ReflectorSetter($method);
			}
		}
	}
}
