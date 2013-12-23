<?php

namespace Papper;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Mapper
{
	/**
	 * @var \ReflectionClass
	 */
	private $sourceReflector;
	/**
	 * @var \ReflectionClass
	 */
	private $destinationReflector;
	/**
	 * @var \Closure
	 */
	private $constructor;

	public function __construct($sourceClass, $destinationClass)
	{
		$this->sourceReflector = new ReflectionClass($sourceClass);
		$this->destinationReflector = new ReflectionClass($destinationClass);
	}

	public function map($source)
	{
		if ($this->sourceReflector->getName() !== get_class($source)) {
			throw new MappingException(sprintf('Source is not instance of class %s', $this->sourceReflector->getName()));
		}

		$object = $this->construct($source);

		foreach ($this->getDestinationSetters() as $name => $setter) {
			$value = $this->getSourceValue($source, $name);

			if ($setter instanceof ReflectionProperty) {
				$setter->setValue($object, $value);
			} else {
				$setter->invokeArgs($object, array($value));
			}
		}

		return $object;
	}

	/**
	 * Construction map
	 *
	 * @param \Closure $constructor
	 */
	public function constructUsing(\Closure $constructor)
	{
		$this->constructor = $constructor;
	}

	private function construct($source)
	{
		if (!is_null($constructor = $this->constructor)) {
			$object = $constructor($source);
			if (!$this->destinationReflector->isInstance($object)) {
				throw new ConstructedUnexpectedDestinationClass(get_class($object), $this->destinationReflector->getName());
			}
			return $object;
		}
		return $this->destinationReflector->newInstance();
	}

	/**
	 * @return ReflectionProperty[]|ReflectionMethod[]
	 */
	private function getDestinationSetters()
	{
		$setters = array();
		foreach ($this->destinationReflector->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$setters[$property->getName()] = $property;
		}
		foreach ($this->destinationReflector->getMethods() as $method) {
			if (strpos($method->getName(), 'set') === 0) {
				$setters[lcfirst(substr($method->getName(), 3))] = $method;
			}
		}
		return $setters;
	}

	private function hasSourceProperty($name)
	{
		return $this->sourceReflector->hasProperty($name) && $this->sourceReflector->getProperty($name)->isPublic();
	}

	private function getSourcePropertyValue($source, $name)
	{
		return $this->sourceReflector->getProperty($name)->getValue($source);
	}

	private function hasSourceMethod($name)
	{
		return $this->sourceReflector->hasMethod($name) && $this->sourceReflector->getMethod($name)->isPublic();
	}

	private function getSourceMethodValue($source, $name)
	{
		return $this->sourceReflector->getMethod($name)->invoke($source);
	}

	private function getSourceValue($source, $name)
	{
		$getter = 'get' . ucfirst($name);
		if ($this->hasSourceMethod($getter)) {
			return $this->getSourceMethodValue($source, $getter);
		} else if ($this->hasSourceProperty($name)) {
			return $this->getSourcePropertyValue($source, $name);
		} else {
			throw new MappingException(sprintf('Could not map property %s', $name));
		}
	}
}
