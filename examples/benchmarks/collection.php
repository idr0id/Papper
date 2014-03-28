<?php

namespace Papper\Examples\Benchmarks\Collection;

use Papper\Papper;

require_once __DIR__ . '/../../vendor/autoload.php';

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

$users = array();
for($i=0; $i < 100000; $i++) {
	$users[] = new User('John Smith', 32);
}

Papper::createMap('Papper\Examples\Benchmarks\Collection\User', 'Papper\Examples\Benchmarks\Collection\UserDTO');

$start = microtime(true);
/** @noinspection PhpUnusedLocalVariableInspection */
$usersDTOs = Papper::map($users, 'Papper\Examples\Benchmarks\Collection\UserDTO', 'Papper\Examples\Benchmarks\Collection\User');
$end = microtime(true);

echo "Mapping time for object[] to object[] (sec): ", $end - $start, PHP_EOL;
