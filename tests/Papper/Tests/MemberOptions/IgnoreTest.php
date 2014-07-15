<?php

namespace Papper\Tests\MemberOptions\Ignore;

use Papper\Engine;
use Papper\MemberOption\Ignore;
use Papper\Tests\FixtureBase;
use Papper\Tests\TestCaseBase;

class IgnoreTest extends TestCaseBase
{
	public function testShouldNotMapIgnoredMembers()
	{
		// arrange
		$engine = new Engine();
		$engine->createMap(Source::className(), Destination::className())
			->forMember('value', new Ignore());
		// act
		/** @var Destination $destination */
		$destination = $engine->map(new Source())->toType(Destination::className());
		// assert
		$this->assertNull($destination->value);
	}

	public function testMapperShouldThrowExceptionWhenSourceMemberMissing()
	{
		$this->setExpectedException('Papper\MappingException');
		// arrange
		$engine = new Engine();
		// act
		$engine->map(new Source())->toType(Destination::className());
	}
}

class Source extends FixtureBase
{
}

class Destination extends FixtureBase
{
	public $value;
}
