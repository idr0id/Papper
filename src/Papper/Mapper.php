<?php

namespace Papper;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class Mapper
{
	private $sourceReflector;
	private $destinationReflector;

	public function __construct($sourceClass, $destinationClass)
	{
		$this->sourceReflector = new ReflectionClass($sourceClass);;
		$this->destinationReflector = new ReflectionClass($destinationClass);
	}

	public function map($source)
	{
		if ($this->sourceReflector->getName() !== get_class($source)) {
			throw new MappingException(sprintf('Source is not instance of class %s', $this->sourceReflector->getName()));
		}

		$object = $this->destinationReflector->newInstance();

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

	/**
	 * @param $source
	 * @param $name
	 * @return mixed
	 * @throws MappingException
	 */
	private function getSourceValue($source, $name)
	{
		$getter = 'get' . ucfirst($name);
		if ($this->hasSourceMethod($getter)) {
			$value = $this->getSourceMethodValue($source, $getter);
			return $value;
		} else if ($this->hasSourceProperty($name)) {
			$value = $this->getSourcePropertyValue($source, $name);
			return $value;
		} else {
			throw new MappingException(sprintf('Could not map property %s', $name));
		}
	}
}
