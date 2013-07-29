<?php

namespace Papper\Tests\PapperImpl;

use Papper\PapperImpl;
use Papper\Tests\TestCaseBase;

class PapperImplTest extends TestCaseBase
{
	public function testCreateMap_Success()
	{
		// arrange
		$papper = $this->createPapperImpl();
		// act
		$map = $papper->createMap(Destination::className(), Source::className());
		// assert
		$this->assertInstanceOf('Papper\Mapper', $map);
	}

	public function testCreateMap_With_IncorrectSourceClass_RaiseException()
	{
		$this->setExpectedException('Papper\ClassNotFoundException');
		// arrange
		$papper = $this->createPapperImpl();
		// act
		$papper->createMap('Papper\Tests\PapperImpl\ClassNotFound', Destination::className());
	}

	public function testCreateMap_With_IncorrectDestinationClass_RaiseException()
	{
		$this->setExpectedException('Papper\ClassNotFoundException');
		// arrange
		$papper = $this->createPapperImpl();
		// act
		$papper->createMap(Destination::className(), 'Papper\Tests\PapperImpl\ClassNotFound');
	}

	public function testCreateMap_For_CreatedMapper_RaiseException()
	{
		$this->setExpectedException('Papper\MapperAlreadyCreatedException');
		// arrange
		$papper = $this->createPapperImpl();
		// act
		$papper->createMap(Source::className(), Destination::className());
		$papper->createMap(Source::className(), Destination::className());
	}

	public function testMap_With_Mapper_Success()
	{
		// arrange
		$papper = $this->createPapperImpl();
		$papper->createMap(Source::className(), Destination::className());
		// act
		$destination = $papper->map(Source::className(), Destination::className(), new Source());
		// assert
		$this->assertInstanceOf(Destination::className(), $destination);
	}

	public function testMap_Without_Mapper_RaiseException()
	{
		$this->setExpectedException('Papper\MapperNotFoundException');
		// arrange
		$papper = $this->createPapperImpl();
		// act
		$papper->map(Source::className(), Destination::className(), new Source());
	}

	private function createPapperImpl()
	{
		return new PapperImpl();
	}
}
