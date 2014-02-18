<?php

namespace Papper\Tests\Context;

use Papper\Context;
use Papper\Tests\TestCaseBase;

class ContextTest extends TestCaseBase
{
	public function testCreateMap_Success()
	{
		// arrange
		$context = new Context();
		// act
		$map = $context->createMap(Destination::className(), Source::className());
		// assert
		$this->assertInstanceOf('Papper\Mapper', $map);
	}

	public function testCreateMap_With_IncorrectSourceClass_RaiseException()
	{
		$this->setExpectedException('Papper\ClassNotFoundException');
		// arrange
		$context = new Context();
		// act
		$context->createMap('Papper\Tests\PapperImpl\ClassNotFound', Destination::className());
	}

	public function testCreateMap_With_IncorrectDestinationClass_RaiseException()
	{
		$this->setExpectedException('Papper\ClassNotFoundException');
		// arrange
		$context = new Context();
		// act
		$context->createMap(Destination::className(), 'Papper\Tests\PapperImpl\ClassNotFound');
	}

	public function testCreateMap_For_CreatedMapper_RaiseException()
	{
		$this->setExpectedException('Papper\ContextException');
		// arrange
		$context = new Context();
		// act
		$context->createMap(Source::className(), Destination::className());
		$context->createMap(Source::className(), Destination::className());
	}

	public function testMap_With_Mapper_Success()
	{
		// arrange
		$context = new Context();
		$context->createMap(Source::className(), Destination::className());
		// act
		$destination = $context->map(Source::className(), Destination::className(), new Source());
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testMap_Without_Mapper_RaiseException()
	{
		$this->setExpectedException('Papper\ContextException');
		// arrange
		$context = new Context();
		// act
		$context->map(Source::className(), Destination::className(), new Source());
	}

	public function testMap_ArrayToArray()
	{
		// arrange
		$context = new Context();
		$context->createMap(Source::className(), Destination::className());

		// act
		$destinations = $context->map(Source::className(), Destination::className(), array(
			new Source('Test data 1'),
			new Source('Test data 2'),
			new Source('Test data 3'),
			new Source('Test data 4'),
			new Source('Test data 5'),
		));

		// assert
		$this->assertCount(5, $destinations);
		$this->assertContainsOnlyInstancesOf(Destination::className(), $destinations);
	}
}
