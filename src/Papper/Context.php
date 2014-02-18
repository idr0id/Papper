<?php

namespace Papper;

class Context
{
	/**
	 * @var Mapper[]
	 */
	private $mappers = array();

	/**
	 * @var Reflector[]
	 */
	private $reflectors = array();

	public function createMap($sourceClass, $destinationClass)
	{
		$this->assertClassExists($sourceClass);
		$this->assertClassExists($destinationClass);
		$this->assertMapperNotCreated($sourceClass, $destinationClass);

		return $this->mappers[$sourceClass][$destinationClass] = new Mapper(
			$this->findOrCreateReflector($sourceClass),
			$this->findOrCreateReflector($destinationClass)
		);
	}

	public function map($sourceType, $destinationType, $source)
	{
		$mapper = $this->get($sourceType, $destinationType);

		return is_array($source)
			? array_map(function($source) use ($mapper) {
					return $mapper->map($source);
				}, $source)
			: $mapper->map($source);
	}

	/**
	 * @param string $sourceClass
	 * @param string $destinationClass
	 * @return Mapper
	 */
	private function get($sourceClass, $destinationClass)
	{
		$this->assertMapperCreated($sourceClass, $destinationClass);

		return $this->mappers[$sourceClass][$destinationClass];
	}

	private function has($sourceClass, $destinationClass)
	{
		return isset($this->mappers[$sourceClass][$destinationClass]);
	}

	/**
	 * @param string $class
	 * @return Reflector
	 */
	private function findOrCreateReflector($class)
	{
		if (!isset($this->reflectors[$class])) {
			$this->reflectors[$class] = new Reflector(new \ReflectionClass($class));
		}
		return $this->reflectors[$class];
	}

	private function assertClassExists($class)
	{
		if (!class_exists($class)) {
			throw new ClassNotFoundException($class);
		}
	}

	private function assertMapperCreated($sourceClass, $destinationClass)
	{
		if (!$this->has($sourceClass, $destinationClass)) {
			throw new ContextException(sprintf('Mapper <%s, %s> not created in context', $sourceClass, $destinationClass));
		}
	}

	private function assertMapperNotCreated($sourceClass, $destinationClass)
	{
		if ($this->has($sourceClass, $destinationClass)) {
			throw new ContextException(sprintf('Mapper <%s, %s> already created', $sourceClass, $destinationClass));
		}
	}
}
