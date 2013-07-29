<?php

namespace Papper;

class PapperImpl
{
	/**
	 * @var Mapper[]
	 */
	private $mappers;

	public function __construct()
	{
		$this->mappers = array();
	}

	public function createMap($sourceClass, $destinationClass)
	{
		$this->assertClassExists($sourceClass);
		$this->assertClassExists($destinationClass);
		$this->assertMapperNotCreated($sourceClass, $destinationClass);

		$mapper = new Mapper($sourceClass, $destinationClass);
		$this->mappers[$sourceClass][$destinationClass] = $mapper;
		return $mapper;
	}

	public function map($sourceType, $destinationType, $source)
	{
		return $this->get($sourceType, $destinationType)->map($source);
	}

	/**
	 * @param string $sourceType
	 * @param string $destinationType
	 * @return Mapper
	 */
	private function get($sourceType, $destinationType)
	{
		$this->assertMapperShouldExists($sourceType, $destinationType);
		return $this->mappers[$sourceType][$destinationType];
	}

	private function assertClassExists($class)
	{
		if (!class_exists($class)) {
			throw new ClassNotFoundException($class);
		}
	}

	private function assertMapperNotCreated($sourceClass, $destinationClass)
	{
		if (isset($this->mappers[$sourceClass][$destinationClass])) {
			throw new MapperAlreadyCreatedException($sourceClass, $destinationClass);
		}
	}

	private function assertMapperShouldExists($sourceClass, $destinationClass)
	{
		if (!isset($this->mappers[$sourceClass][$destinationClass])) {
			throw new MapperNotFoundException($sourceClass, $destinationClass);
		}
	}
}
