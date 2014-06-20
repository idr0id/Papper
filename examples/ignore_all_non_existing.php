<?php

namespace Papper\Examples\IgnoreAllNonExisting;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;
	public $family;

	public function __construct($name, $family)
	{
		$this->name = $name;
		$this->family = $family;
	}
}

class UserDTO
{
	public $name;
	public $family;
	public $unmappedProperty;
}

Papper::createMap('Papper\Examples\IgnoreAllNonExisting\User', 'Papper\Examples\IgnoreAllNonExisting\UserDTO')
	->ignoreAllNonExisting();

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John', 'Smith'))->to(new UserDTO());

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Family: ", $userDTO->family, PHP_EOL;
echo "Unmapped property: ", $userDTO->unmappedProperty, PHP_EOL;

// The above example will output:
//
// Name: John
// Family: Smith
// Unmapped property:
