<?php

namespace Papper\Examples\Collection;

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
	$users[] = new User('John Smith ' . $i);
}

$usersDTOs = Papper::map($users, 'Papper\Examples\Collection\User')->toType('Papper\Examples\Collection\UserDTO');

print_r($usersDTOs);
