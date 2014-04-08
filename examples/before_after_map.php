<?php

namespace Papper\Examples\BeforeAfterMap;

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
	private $actions = array();

	public function doBefore()
	{
		$this->actions[] = 'before!';
	}

	public function doAfter()
	{
		$this->actions[] = 'after!';
	}

	public function getActions()
	{
		return $this->actions;
	}
}

Papper::createMap('Papper\Examples\BeforeAfterMap\User', 'Papper\Examples\BeforeAfterMap\UserDTO')
	->beforeMap(function(User $source, UserDTO $destination) {
		$destination->doBefore();
	})
	->afterMap(function(User $source, UserDTO $destination) {
		$destination->doAfter();
	});

$userDTO = Papper::map(new User('John Smith'))->toType('Papper\Examples\BeforeAfterMap\UserDTO');

echo 'Name: ', $userDTO->name, PHP_EOL;
echo 'Actions: ', print_r($userDTO->getActions(), true), PHP_EOL;
