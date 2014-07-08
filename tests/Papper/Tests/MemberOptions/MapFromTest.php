<?php

namespace Papper\Tests\MemberOptions\MapFrom;

use Papper\MemberOption\MapFrom;
use Papper\Tests\FixtureBase;
use Papper\Tests\TestCaseBase;

class MapFromTest extends TestCaseBase
{
	public function testShouldMapFromAnotherValue()
	{
		// arrange
		$engine = $this->createEngine();
		$engine->createMap(User::className(), UserDTO::className())
			->forMember('fullName', new MapFrom(function(User $source) {
				return $source->firstName . ' ' . $source->lastName;
			}))
			->forMember('numberOfYears', new MapFrom('age'));

		$user = new User();
		$user->firstName = 'John';
		$user->lastName = 'Smith';
		$user->age = 26;
		// act
		/** @var UserDTO $dest */
		$dest = $engine->map($user)->toType(UserDTO::className());
		// assert
		$this->assertEquals('John Smith', $dest->fullName);
		$this->assertEquals(26, $dest->numberOfYears);
	}
}

class User extends FixtureBase
{
	public $firstName;
	public $lastName;
	public $age;
}

class UserDTO extends FixtureBase
{
	public $fullName;
	public $numberOfYears;
}
