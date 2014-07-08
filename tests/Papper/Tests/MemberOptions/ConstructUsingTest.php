<?php

namespace Papper\Tests\MemberOptions\ConstructUsing;

use Papper\Engine;
use Papper\Tests\FixtureBase;
use Papper\Tests\TestCaseBase;

class ConstructUsingTest extends TestCaseBase
{
	public function testMappingUsingCustomConstructor()
	{
		// arrange
		$engine = new Engine();
		$engine->createMap(Source::className(), Destination::className())
			->constructUsing(function (Source $source){
				return new Destination($source->value);
			});
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new Source('Some value'))->toType(Destination::className());
		// assert
		$this->assertEquals('Some value', $destination->getCtorValue());
	}

	public function testMappingShouldThrowExceptionWhenCreatedInvalidDestinationClass()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		$engine->createMap(Source::className(), Destination::className())
			->constructUsing(function (Source $source){
				return new InvalidDestination($source->value);
			});
		// act
		$engine->map(new Source('Some value'))->toType(Destination::className());
	}
}

class Source extends FixtureBase
{
	public $value;

	public function __construct($value)
	{
		$this->value = $value;
	}
}

class Destination extends FixtureBase
{
	private $ctorValue;

	public function __construct($ctorValue)
	{
		$this->ctorValue = $ctorValue;
	}

	public function getCtorValue()
	{
		return $this->ctorValue;
	}
}

class InvalidDestination
{
}
