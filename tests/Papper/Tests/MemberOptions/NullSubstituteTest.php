<?php

namespace Papper\Tests\MemberOptions\NullSubstitute;

use Papper\MemberOption\NullSubstitute;
use Papper\Tests\FixtureBase;
use Papper\Tests\TestCaseBase;

/**
 * Class NullSubstituteTest
 *
 * @
 */
class NullSubstituteTest extends TestCaseBase
{
	public function testMap()
	{
		// arrange
		$source = new Source();
		$source->value = null;

		$engine = $this->createEngine();
		$engine->createMap(Source::className(), Destination::className())
			->forMember('value', new NullSubstitute('I am null!'));

		// act
		/** @var Destination $dest */
		$dest = $engine->map($source)->toType(Destination::className());

		// assert
		$this->assertEquals('I am null!', $dest->value);
	}
}

class Source extends FixtureBase
{
	public $value;
}

class Destination extends FixtureBase
{
	public $value;
}
