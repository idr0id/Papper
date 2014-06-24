<?php

namespace Papper\Examples\TraitForClassName;

use Papper\ClassName;
use Papper\MemberOption\Ignore;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	use ClassName;

	public $name = 'John Smith';
	public $age = 32;
}

class UserDTO
{
	use ClassName;

	public $name;
	public $age;
}

Papper::createMap(User::className(), UserDTO::className())
	->forMember('age', new Ignore());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User())->toType(UserDTO::className());

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\TraitForClassName\UserDTO Object
 * (
 *     [name] => John Smith
 *     [age] =>
 * )
 *
 */
