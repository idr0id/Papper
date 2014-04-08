<?php

namespace Papper\Examples\Simple;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;
	private $age;

	public function __construct($name, $age)
	{
		$this->name = $name;
		$this->age = $age;
	}

	public function getAge()
	{
		return $this->age;
	}
}

class UserDTO
{
	public $name;
	private $age;

	public function getAge()
	{
		return $this->age;
	}

	public function setAge($age)
	{
		$this->age = $age;
	}
}

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', 32))->toType('Papper\Examples\Simple\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->getAge(), PHP_EOL;
