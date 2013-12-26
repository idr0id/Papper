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
		if (!$this->sourceReflector->isInstance($source)) {
			throw new MappingException(sprintf('Source is not instance of class %s', $this->sourceReflector->getName()));
		}

		$object = $this->construct($source);

		foreach ($this->destinationReflector->getSetters() as $name => $setter) {
			if (in_array($name, $this->ignoredSetters)) {
				continue;
			}
			if (!$this->sourceReflector->hasGetter($name)) {
				throw new MappingException(sprintf('Could not map property %s', $name));
			}
			$getter = $this->sourceReflector->getGetter($name);
			$setter->set($object, $getter->getValue($source));
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
			if (!$this->destinationReflector->isInstance($object)) {
				throw new MappingException(sprintf(
					'Closure constructed class %s, but expected %s', get_class($object), $this->destinationReflector->getName()
				));
			}
			return $object;
		}
		return $this->destinationReflector->create();
	}
}
