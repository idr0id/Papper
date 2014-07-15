<?php

namespace Papper\Examples\MappingNullValue;

use Papper\MemberOption\NullSubstitute;
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
}

Papper::createMap('Papper\Examples\MappingNullValue\User', 'Papper\Examples\MappingNullValue\UserDTO')
	->forMember('name', new NullSubstitute('Unknown name'));

$userDTO = Papper::map(new User(null))->toType('Papper\Examples\MappingNullValue\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MappingNullValue\UserDTO Object
 * (
 *     [name] => Unknown name
 * )
 *
 */
