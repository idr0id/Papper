<?php

namespace Papper\Examples\MappingFromSpecificSourceMember;

use Papper\MemberOption\MapFrom;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John';
	public $family = 'Smith';
	public $ageOfUser = 32;
}

class UserDTO
{
	public $name;
	public $age;
}

Papper::createMap('Papper\Examples\MappingFromSpecificSourceMember\User', 'Papper\Examples\MappingFromSpecificSourceMember\UserDTO')
	->forMember('name', new MapFrom(function(User $source) {
		return $source->family . ' ' . $source->name;
	}))
	->forMember('age', new MapFrom('ageOfUser'));

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User())->toType('Papper\Examples\MappingFromSpecificSourceMember\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MappingFromSpecificSourceMember\UserDTO Object
 * (
 *     [name] => Smith John
 *     [age] => 32
 * )
 *
 */
