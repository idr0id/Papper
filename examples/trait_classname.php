<?php

namespace Papper\Examples\TraitClassName;

use Papper\ClassName;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	use ClassName;

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
	use ClassName;

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

Papper::createMap(User::className(), UserDTO::className());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', 32))->toType(UserDTO::className());

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->getAge(), PHP_EOL;
