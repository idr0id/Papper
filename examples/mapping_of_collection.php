<?php

namespace Papper\Examples\MappingToCollection;

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

$users = array();
for($i=0; $i < 5; $i++) {
	$users[] = new User('John Smith #' . $i);
}

$usersDTOs = Papper::map($users, 'Papper\Examples\MappingToCollection\User')->toType('Papper\Examples\MappingToCollection\UserDTO');

print_r($usersDTOs);

/*
 * The above example will output:
 *
 * Array
 * (
 *     [0] => Papper\Examples\MappingToCollection\UserDTO Object
 *         (
 *             [name] => John Smith #0
 *         )
 *
 *     [1] => Papper\Examples\MappingToCollection\UserDTO Object
 *         (
 *             [name] => John Smith #1
 *         )
 *
 *     [2] => Papper\Examples\MappingToCollection\UserDTO Object
 *         (
 *             [name] => John Smith #2
 *         )
 *
 *     [3] => Papper\Examples\MappingToCollection\UserDTO Object
 *         (
 *             [name] => John Smith #3
 *         )
 *
 *     [4] => Papper\Examples\MappingToCollection\UserDTO Object
 *         (
 *             [name] => John Smith #4
 *         )
 *
 * )
 *
 */
