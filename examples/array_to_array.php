<?php

namespace Papper\Examples\ArrayToArray;

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
}

Papper::createMap('Papper\Examples\ArrayToArray\User', 'Papper\Examples\ArrayToArray\UserDTO');

$users = array();
for($i=0; $i < 100000; $i++) {
	$users[] = new User('John Smith ' . $i);
}

$start = microtime(true);
$usersDTOs = Papper::map('Papper\Examples\ArrayToArray\User', 'Papper\Examples\ArrayToArray\UserDTO', $users);
$end = microtime(true);

echo "Mapping time (sec): ", $end - $start, PHP_EOL;
