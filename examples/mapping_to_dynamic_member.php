<?php

namespace Papper\Examples\MapToDynamicProperties;

use Papper\MemberOption\MapFrom;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John';
	public $family = 'Smith';
	public $age = 32;
}

class UserDTO
{
	public function __construct()
	{
		$this->fullname = null;
		$this->age = null;
	}
}

Papper::createMap('Papper\Examples\MapToDynamicProperties\User', 'Papper\Examples\MapToDynamicProperties\UserDTO')
	->forDynamicMember('fullname', new MapFrom(function(User $s) {
		return $s->name . ' ' . $s->family;
	}))
	->forDynamicMember('age', new MapFrom('age'))
;

/** @var \stdClass $userDTO */
$userDTO = Papper::map(new User())->to(new UserDTO());

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MapToDynamicProperties\UserDTO Object
 * (
 *     [fullname] => John Smith
 *     [age] => 32
 * )
 *
 */
