<?php

namespace Papper\Examples\IgnoreMemberForSkipDuringMapping;

use Papper\MemberOption\Ignore;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;

	public function __construct($name)
	{
		$this->name = $name;
	}
}

class UserDTO
{
	public $name;
	public $age = 'default value of ignored member';
}

$user = new User('John Smith');

Papper::createMap('Papper\Examples\IgnoreMemberForSkipDuringMapping\User', 'Papper\Examples\IgnoreMemberForSkipDuringMapping\UserDTO')
	->forMember('age', new Ignore());

/** @var UserDTO $userDTO */
$userDTO = Papper::map($user)->toType('Papper\Examples\IgnoreMemberForSkipDuringMapping\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\IgnoreMemberForSkipDuringMapping\UserDTO Object
 * (
 *     [name] => John Smith
 *     [age] => default value of ignored member
 * )
 *
 */
