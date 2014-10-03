<?php

namespace Papper\Examples\Caching;

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

Papper::enableCaching(__DIR__ . DIRECTORY_SEPARATOR . 'cache');
Papper::createMap('Papper\Examples\Caching\User', 'Papper\Examples\Caching\UserDTO');

$users = array();
for($i = 0; $i < 10000; $i++) {
	$users[] = new User('John Smith' . $i, $i);
}


$x = microtime(true);
/** @var UserDTO $userDTO */
$userDTO = Papper::map($users, 'Papper\Examples\Caching\User')->toType('Papper\Examples\Caching\UserDTO');

echo microtime(true) - $x;
