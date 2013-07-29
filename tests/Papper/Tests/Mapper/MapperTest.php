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

	private function createMap($sourceClass, $destinationClass)
	{
		return new Mapper($sourceClass, $destinationClass);
	}
}
