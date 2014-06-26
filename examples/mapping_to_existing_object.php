<?php

namespace Papper\Examples\MappingToExistsObject;

use Papper\MemberOption\Ignore;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John Smith';
}

class UserDTO
{
	public $id;
	public $name;

	public function __construct($id)
	{
		$this->id = $id;
	}
}

Papper::createMap('Papper\Examples\MappingToExistsObject\User', 'Papper\Examples\MappingToExistsObject\UserDTO')
	->forMember('id', new Ignore());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User())->to(new UserDTO(123));

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MappingToExistsObject\UserDTO Object
 * (
 *     [id] => 123
 *     [name] => John Smith
 * )
 *
 */
