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
		$destination = $engine->map(new Source(), Destination::className()); /** @var Destination $destination */
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testMapperShouldMapPublicProperties()
	{
		// arrange
		$engine = new Engine();
		// act
		$destination = $engine->map(new Source(), Destination::className()); /** @var Destination $destination */
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMapperShouldMapFromGetter()
	{
		// arrange
		$engine = new Engine();
		// act
		$destination = $engine->map(new SourceWithGetter(), Destination::className()); /** @var Destination $destination */
		// assert
		$this->assertEquals('Some value', $destination->someValue);
	}

	public function testMapperShouldMapToSetter()
	{
		// arrange
		$engine = new Engine();
		// act
		$destination = $engine->map(new Source(), DestinationWithSetter::className()); /** @var DestinationWithSetter $destination */
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMapperShouldConstructUsingClosure()
	{
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(Source::className(), DestinationWithConstructor::className())
			->constructUsing(function (Source $source){
				return new DestinationWithConstructor($source->someValue);
			});
		// act
		$destination = $engine->map(new Source(), DestinationWithConstructor::className()); /** @var DestinationWithConstructor $destination */
		// assert
		$this->assertEquals('Some value', $destination->getSomeValue());
	}

	public function testMapperShouldRaiseExceptionWhenConstructNotExpectedDestinationClass()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(Source::className(), Destination::className())
			->constructUsing(function (Source $source){
				return new DestinationWithConstructor($source->someValue);
			});
		// act
		$engine->map(new Source(), Destination::className());
	}

	public function testMapperShouldIgnoreMappingToPropertyOrSetter()
	{
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(SourceEmpty::className(), Destination::className())
			->forMember('someValue', new Ignore());
		// act
		$destination = $engine->map(new SourceEmpty(), Destination::className()); /** @var Destination $destination */
		// assert
		$this->assertNull($destination->someValue);
	}

	public function testMapperShouldRaiseExceptionWhenSourceHaveNotProperty()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		// act
		$engine->map(new SourceEmpty(), Destination::className());
	}
}
