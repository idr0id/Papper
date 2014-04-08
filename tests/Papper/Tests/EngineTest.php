<?php

namespace Papper\Tests;

use Papper\Engine;
use Papper\MemberOption\Ignore;
use Papper\Tests\Fixtures\Destination;
use Papper\Tests\Fixtures\DestinationWithConstructor;
use Papper\Tests\Fixtures\DestinationWithSetter;
use Papper\Tests\Fixtures\Source;
use Papper\Tests\Fixtures\SourceEmpty;
use Papper\Tests\Fixtures\SourceWithGetter;

class EngineTest extends TestCaseBase
{
	public function testMapperShouldCreateInstanceOfDestinationClass()
	{
		// arrange
		$engine = new Engine();
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new Source())->toType(Destination::className());
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testMappingFromPropertyToProperties()
	{
		// arrange
		$engine = new Engine();
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new Source())->toType(Destination::className());
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMappingFromGetterToProperty()
	{
		// arrange
		$engine = new Engine();
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new SourceWithGetter())->toType(Destination::className());
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMappingFromPropertyToToSetter()
	{
		// arrange
		$engine = new Engine();
		// act
		/** @var DestinationWithSetter $destination */
		$destination = $engine->map(new Source())->toType(DestinationWithSetter::className());
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMappingUsingCustomConstructor()
	{
		// arrange
		$engine = new Engine();
		$engine->createMap(Source::className(), DestinationWithConstructor::className())
			->constructUsing(function (Source $source){
				return new DestinationWithConstructor($source->someValue);
			});
		// act
		/** @var DestinationWithConstructor $destination */
		$destination = $engine->map(new Source())->toType('\\' . DestinationWithConstructor::className());
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMappingShouldThrowExceptionWhenCreatedInvalidDestinationClass()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		$engine->createMap(Source::className(), Destination::className())
			->constructUsing(function (Source $source){
				return new DestinationWithConstructor($source->someValue);
			});
		// act
		$engine->map(new Source())->toType(Destination::className());
	}

	public function testMappingShouldNotMapIgnoredMembers()
	{
		// arrange
		$engine = new Engine();
		$engine->createMap(SourceEmpty::className(), Destination::className())
			->forMember('someValue', new Ignore());
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new SourceEmpty())->toType(Destination::className());
		// assert
		$this->assertNull($destination->someValue);
	}

	public function testMapperShouldThrowExceptionWhenSourceMemberMissing()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		// act
		$engine->map(new SourceEmpty())->toType(Destination::className());
	}
}
