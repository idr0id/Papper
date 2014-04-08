<?php

namespace Papper\Examples\Constructor;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;
	public $age;

	public function __construct($name, $age)
	{
		$this->name = $name;
		$this->age = $age;
	}
}

class UserDTO
{
	private $name;
	private $age;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getAge()
	{
		return $this->age;
	}

	public function setAge($age)
	{
		$this->age = $age;
	}
}

Papper::createMap('Papper\Examples\Constructor\User', 'Papper\Examples\Constructor\UserDTO')
	->constructUsing(function (User $user) {
		return new UserDTO($user->name);
	});

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', 32))->toType('Papper\Examples\Constructor\UserDTO');

echo "Name: ", $userDTO->getName(), PHP_EOL;
echo "Age: ", $userDTO->getAge(), PHP_EOL;
