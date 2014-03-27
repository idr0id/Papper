<?php

namespace Papper\Tests;

use Papper\Tests\ObjectMother\ObjectCreatorMother;
use Papper\Tests\ObjectMother\PropertyMapMother;
use Papper\TypeMap;

class TypeMapTest extends TestCaseBase
{
	public function testGetUnmappedProperty()
	{
		// arrange
		$mappedPropertyMap = PropertyMapMother::createMapped();
		$unmappedPropertyMap = PropertyMapMother::createUnmapped();

		$typeMap = new TypeMap('SomeType', 'AnotherType', ObjectCreatorMother::create());
		$typeMap->addPropertyMap($mappedPropertyMap);
		$typeMap->addPropertyMap($unmappedPropertyMap);
		// act
		$unmappedProperties = $typeMap->getUnmappedProperties();
		// assert
		$this->assertCount(1, $unmappedProperties);
		$this->assertSame($unmappedPropertyMap, $unmappedProperties['unmapped']);
	}

	public function testValidationWithUnmappedPropertiesShouldPass()
	{
		// arrange
		$typeMap = new TypeMap('SomeType', 'AnotherType', ObjectCreatorMother::create());
		$typeMap->addPropertyMap(PropertyMapMother::createMapped());
		// act
		$typeMap->validate();
	}

	public function testValidationWithUnmappedPropertiesShouldThrowException()
	{
		$this->setExpectedException('Papper\ValidationException');
		// arrange
		$typeMap = new TypeMap('SomeType', 'AnotherType', ObjectCreatorMother::create());
		$typeMap->addPropertyMap(PropertyMapMother::createMapped());
		$typeMap->addPropertyMap(PropertyMapMother::createUnmapped());
		// act
		$typeMap->validate();
	}
}
