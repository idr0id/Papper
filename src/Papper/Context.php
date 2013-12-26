<?php

namespace Papper;

class Context
{
	/**
	 * @var Mapper[]
	 */
	private $mappers = array();

	public function createMap($sourceClass, $destinationClass)
	{
		$this->assertClassExists($sourceClass);
		$this->assertClassExists($destinationClass);
		$this->assertMapperNotCreated($sourceClass, $destinationClass);

		return $this->mappers[$sourceClass][$destinationClass] = new Mapper($sourceClass, $destinationClass);
	}

	public function map($sourceType, $destinationType, $source)
	{
		return $this->get($sourceType, $destinationType)->map($source);
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
