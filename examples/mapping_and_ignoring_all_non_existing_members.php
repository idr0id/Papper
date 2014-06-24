<?php

namespace Papper\Examples\IgnoreAllNonExisting;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John';
	public $family = 'Smith';
}

class UserDTO
{
	public $name;
	public $family;
	public $unmappedProperty1;
	public $unmappedProperty2;
	public $unmappedProperty3;
	public $unmappedProperty4;
	public $unmappedProperty5;
}

Papper::createMap('Papper\Examples\IgnoreAllNonExisting\User', 'Papper\Examples\IgnoreAllNonExisting\UserDTO')
	->ignoreAllNonExisting();

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User())->to(new UserDTO());

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\IgnoreAllNonExisting\UserDTO Object
 * (
 *     [name] => John
 *     [family] => Smith
 *     [unmappedProperty1] =>
 *     [unmappedProperty2] =>
 *     [unmappedProperty3] =>
 *     [unmappedProperty4] =>
 *     [unmappedProperty5] =>
 * )
 */
