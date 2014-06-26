<?php

namespace Papper\Examples\MappingWithCustomConstructor;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John Smith';
	public $age = 32;
}

class UserDTO
{
	private $name;
	public $age;

	public function __construct($name)
	{
		$this->name = $name;
	}
}

Papper::createMap('Papper\Examples\MappingWithCustomConstructor\User', 'Papper\Examples\MappingWithCustomConstructor\UserDTO')
	->constructUsing(function (User $user) {
		return new UserDTO($user->name);
	});

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User())->toType('Papper\Examples\MappingWithCustomConstructor\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MappingWithCustomConstructor\UserDTO Object
 * (
 *     [name:Papper\Examples\MappingWithCustomConstructor\UserDTO:private] => John Smith
 *     [age] => 32
 * )
 *
 */
