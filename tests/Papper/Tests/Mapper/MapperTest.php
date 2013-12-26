<?php

namespace Papper\Tests\Mapper;

use Papper\Mapper;
use Papper\Reflector;
use Papper\Tests\TestCaseBase;
use ReflectionClass;

class MapperTest extends TestCaseBase
{
	public function testMapper_Should_CreateInstanceOfDestinationClass()
	{
		// arrange
		$map = $this->createMap(Source::className(), Destination::className());
		// act
		$destination = $map->map(new Source()); /** @var Destination $destination */
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testMapper_Should_MapPublicProperties()
	{
		// arrange
		$map = $this->createMap(Source::className(), Destination::className());
		// act
		$destination = $map->map(new Source()); /** @var Destination $destination */
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMapper_Should_MapFromGetter()
	{
		// arrange
		$map = $this->createMap(SourceWithGetter::className(), Destination::className());
		// act
		$destination = $map->map(new SourceWithGetter()); /** @var Destination $destination */
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMapper_Should_MapToSetter()
	{
		// arrange
		$map = $this->createMap(Source::className(), DestinationWithSetter::className());
		// act
		$destination = $map->map(new Source()); /** @var DestinationWithSetter $destination */
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMapper_Should_ConstructUsingClosure()
	{
		// arrange
		$map = $this->createMap(Source::className(), DestinationWithConstructor::className());
		$map->constructUsing(function (Source $source){
			return new DestinationWithConstructor($source->someValue);
		});
		// act
		$destination = $map->map(new Source()); /** @var DestinationWithConstructor $destination */
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMapper_Should_RaiseException_When_ConstructNotExpectedDestinationClass()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$map = $this->createMap(Source::className(), Destination::className());
		$map->constructUsing(function (Source $source){
			return new DestinationWithConstructor($source->someValue);
		});
		// act
		$map->map(new Source());
	}

	public function testMapper_Should_IgnoreMappingToPropertyOrSetter()
	{
		// arrange
		$map = $this->createMap(SourceEmpty::className(), Destination::className());
		$map->ignore('someValue');
		// act
		$destination = $map->map(new SourceEmpty()); /** @var Destination $destination */
		// assert
		$this->assertNull($destination->someValue);
	}

	public function testMapper_Should_RaiseException_When_SourceHaveNotProperty()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$map = $this->createMap(SourceEmpty::className(), Destination::className());
		// act
		$map->map(new SourceEmpty());
	}

	private function createMap($sourceClass, $destinationClass)
	{
		return new Mapper($this->createReflector($sourceClass), $this->createReflector($destinationClass));
	}

	private function createReflector($class)
	{
		return new Reflector(new ReflectionClass($class));
	}
}
