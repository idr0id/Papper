<?php

namespace Papper;

class Mapper
{
	/**
	 * @var Reflector
	 */
	private $sourceReflector;
	/**
	 * @var Reflector
	 */
	private $destinationReflector;
	/**
	 * @var \Closure
	 */
	private $constructor;
	/**
	 * @var array
	 */
	private $ignoredSetters = array();

	public function __construct(Reflector $sourceReflector, Reflector $destinationReflector)
	{
		$this->sourceReflector = $sourceReflector;
		$this->destinationReflector = $destinationReflector;
	}

	/**
	 * Map
	 *
	 * @param object $source
	 * @return object
	 * @throws MappingException
	 */
	public function map($source)
	{
		$this->assertSourceIsObject($source);
		$this->assertSourceObjectIsInstanceOfSourceClass($source);

		$object = $this->construct($source);

		foreach ($this->destinationReflector->getSetters() as $name => $setter) {
			if (in_array($name, $this->ignoredSetters)) {
				continue;
			}
			if (!$this->sourceReflector->hasGetter($name)) {
				throw new MappingException(sprintf('Could not map property %s', $name));
			}
			$setter->setValue($object, $this->sourceReflector->getGetter($name)->getValue($source));
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

	/**
	 * Make property ignored
	 *
	 * @param string $setter
	 */
	public function ignore($setter)
	{
		$this->ignoredSetters[] = $setter;
	}

	private function construct($source)
	{
		if (!is_null($constructor = $this->constructor)) {
			$object = $constructor($source);
			$this->assertConstructedIsObject($object);
			$this->assertConstructedObjectIsInstanceOfDestinationClass($object);
			return $object;
		}
		return $this->destinationReflector->create();
	}

	private function assertSourceIsObject($source)
	{
		if (!is_object($source)) {
			throw new MappingException(sprintf(
				'Source type is %s, but expected an object of class %s', gettype($source), $this->sourceReflector->getName()
			));
		}
	}

	private function assertSourceObjectIsInstanceOfSourceClass($source)
	{
		if (!$this->sourceReflector->isInstance($source)) {
			throw new MappingException(sprintf(
				'Source object is instance of class %s, but expected an object of class %s', get_class($source), $this->sourceReflector->getName()
			));
		}
	}

	private function assertConstructedIsObject($constructed)
	{
		if (!is_object($constructed)) {
			throw new MappingException(sprintf(
				'Constructed type is %s, but expected an object of class %s', get_class($constructed), $this->destinationReflector->getName()
			));
		}
	}

	private function assertConstructedObjectIsInstanceOfDestinationClass($constructed)
	{
		if (!$this->destinationReflector->isInstance($constructed)) {
			throw new MappingException(sprintf(
				'Constructed object is instance of class %s, but expected an object of class %s', get_class($constructed), $this->destinationReflector->getName()
			));
		}
	}


}
