<?php

namespace Papper\Tests;

use Papper\Engine;

class MappingEngineTest extends TestCaseBase
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
		$source = new Source();
		$source->propertyToProperty = 'property to property';
		$source->propertyToSetter = 'property to setter';
		$source->setGetterToProperty('getter to property');
		$source->setGetterToSetter('getter to setter');
		// act
		/** @var Destination $destination */
		$destination = $engine->map($source)->toType(Destination::className());
		// assert
		$this->assertEquals('property to property', $destination->propertyToProperty);
		$this->assertEquals('property to setter', $destination->getPropertyToSetter());
		$this->assertEquals('getter to property', $destination->getterToProperty);
		$this->assertEquals('getter to setter', $destination->getGetterToSetter());
	}
}

class Source extends FixtureBase
{
	public $propertyToProperty;
	public $propertyToSetter;
	private $getterToProperty;
	private $getterToSetter;

	public function getGetterToProperty()
	{
		return $this->getterToProperty;
	}

	public function setGetterToProperty($getterToProperty)
	{
		$this->getterToProperty = $getterToProperty;
	}

	public function getGetterToSetter()
	{
		return $this->getterToSetter;
	}

	public function setGetterToSetter($getterToSetter)
	{
		$this->getterToSetter = $getterToSetter;
	}
}

class Destination extends FixtureBase
{
	public $propertyToProperty;
	private $propertyToSetter;
	public $getterToProperty;
	private $getterToSetter;

	public function getGetterToSetter()
	{
		return $this->getterToSetter;
	}

	public function setGetterToSetter($getterToSetter)
	{
		$this->getterToSetter = $getterToSetter;
	}

	public function getPropertyToSetter()
	{
		return $this->propertyToSetter;
	}

	public function setPropertyToSetter($propertyToSetter)
	{
		$this->propertyToSetter = $propertyToSetter;
	}
}
