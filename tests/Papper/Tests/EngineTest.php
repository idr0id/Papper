<?php

namespace Papper\Tests;

use Papper\Engine;
use Papper\Tests\Fixtures\Destination;
use Papper\Tests\Fixtures\Source;

class EngineTest extends TestCaseBase
{
	public function testCreatedTypeMap_Should_HaveTypeMapInterface()
	{
		// arrange
		$engine = new Engine();
		// act
		$map = $engine->createTypeMap(Destination::className(), Source::className());
		// assert
		$this->assertInstanceOf('Papper\TypeMapInterface', $map);
	}

	public function testObjectToObjectMapping()
	{
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(Source::className(), Destination::className());
		// act
		$destination = $engine->map(new Source(), Destination::className());
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testTypeMap_Should_CreatingBeforeMapping()
	{
		// arrange
		$engine = new Engine();
		// act
		$destination = $engine->map(new Source(), Destination::className());
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testArrayToArrayMapping()
	{
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(Source::className(), Destination::className());

		$sources = array(
			new Source('Test data 1'),
			new Source('Test data 2'),
			new Source('Test data 3'),
			new Source('Test data 4'),
			new Source('Test data 5'),
		);

		// act
		$destinations = $engine->map($sources, Destination::className(), Source::className());

		// assert
		$this->assertCount(5, $destinations);
		$this->assertContainsOnlyInstancesOf(Destination::className(), $destinations);
	}

	/**
	 * @expectedException \Papper\MappingException
	 * @expectedExceptionMessage Source type should specified explicitly for array mapping
	 */
	public function testArrayToArrayMapping_Should_RaiseExeption_When_NotSpecifiedSourceType()
	{
		// arrange
		$engine = new Engine();
		$engine->createTypeMap(Source::className(), Destination::className());

		$sources = array(
			new Source('Test data 1'),
			new Source('Test data 2'),
			new Source('Test data 3'),
			new Source('Test data 4'),
			new Source('Test data 5'),
		);

		// act
		$engine->map($sources, Destination::className());
	}
}
