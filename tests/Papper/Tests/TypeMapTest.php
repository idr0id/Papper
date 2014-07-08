<?php

namespace Papper\Tests;

use Papper\TypeMap;

class TypeMapTest extends TestCaseBase
{
	public function testGetUnmappedProperty()
	{
		// arrange
		$mappedPropertyMap = self::createMappedProperty();
		$unmappedPropertyMap = self::createUnmappedProperty();

		$typeMap = new TypeMap('SomeType', 'AnotherType', \Mockery::mock('Papper\ObjectCreatorInterface'));
		$typeMap->addPropertyMap($mappedPropertyMap);
		$typeMap->addPropertyMap($unmappedPropertyMap);
		// act
		$unmappedProperties = $typeMap->getUnmappedPropertyMaps();
		// assert
		$this->assertCount(1, $unmappedProperties);
		$this->assertSame($unmappedPropertyMap, $unmappedProperties['unmapped']);
	}

	public function testValidationWithUnmappedPropertiesShouldPass()
	{
		// arrange
		$typeMap = new TypeMap('SomeType', 'AnotherType', \Mockery::mock('Papper\ObjectCreatorInterface'));
		$typeMap->addPropertyMap(self::createMappedProperty());
		// act
		$typeMap->validate();
	}

	public function testValidationWithUnmappedPropertiesShouldThrowException()
	{
		$this->setExpectedException('Papper\ValidationException');
		// arrange
		$typeMap = new TypeMap('SomeType', 'AnotherType', \Mockery::mock('Papper\ObjectCreatorInterface'));
		$typeMap->addPropertyMap(self::createMappedProperty());
		$typeMap->addPropertyMap(self::createUnmappedProperty());
		// act
		$typeMap->validate();
	}

	private static function createMappedProperty()
	{
		$mock = \Mockery::mock('Papper\PropertyMap');
		$mock->shouldReceive('getMemberName')->andReturn('mapped');
		$mock->shouldReceive('isMapped')->andReturn(true);
		return $mock;
	}

	private static function createUnmappedProperty()
	{
		$mock = \Mockery::mock('Papper\PropertyMap');
		$mock->shouldReceive('getMemberName')->andReturn('unmapped');
		$mock->shouldReceive('isMapped')->andReturn(false);
		return $mock;
	}
}
