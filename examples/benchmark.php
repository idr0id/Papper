<?php

namespace Papper\Examples\Benchmark;

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

Papper::createMap('Papper\Examples\Benchmark\User', 'Papper\Examples\Benchmark\UserDTO');

$start = microtime(true);
for($i=0; $i < 100000; $i++) {
	Papper::map('Papper\Examples\Benchmark\User', 'Papper\Examples\Benchmark\UserDTO', new User('John Smith', 32));
}
$end = microtime(true);

echo "Mapping time (sec): ", $end - $start, PHP_EOL;
