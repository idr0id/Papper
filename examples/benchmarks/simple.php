<?php

namespace Papper\Examples\Benchmarks\Simple;

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

$start = microtime(true);
$usersDTOs = Papper::map(new User('John Smith', 32), 'Papper\Examples\Benchmarks\Simple\UserDTO');
$end = microtime(true);

echo "Mapping time for object to object (sec): ", $end - $start, PHP_EOL;

