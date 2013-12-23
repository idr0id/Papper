<?php

namespace Papper\Tests\Mapper;

use Papper\Mapper;
use Papper\Tests\TestCaseBase;

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

	public function testMapper_Should_ConstructUsingClosureFactory()
	{
		// arrange
		$map = $this->createMap(Source::className(), DestinationWithConstructor::className());
		$map->constructUsing(function (Source $source){
			return new DestinationWithConstructor($source->someValue);
		});
		// act
		$destination = $map->map(new Source()); /** @var DestinationWithSetter $destination */
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMapper_Should_RaiseException_When_ConstructNotExpectedDestinationClass()
	{
		$this->setExpectedException('Papper\ConstructedUnexpectedDestinationClass');
		// arrange
		$map = $this->createMap(Source::className(), Destination::className());
		$map->constructUsing(function (Source $source){
			return new DestinationWithConstructor($source->someValue);
		});
		// act
		$map->map(new Source());
	}

	private function createMap($sourceClass, $destinationClass)
	{
		return new Mapper($sourceClass, $destinationClass);
	}
}
