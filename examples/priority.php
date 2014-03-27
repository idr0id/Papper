<?php

namespace Papper\Examples\Priority;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name . ' - from method';
	}
}

class UserDTO
{
	public $name;
	private $internalName;

	public function getName()
	{
		return $this->internalName;
	}

	public function setName($name)
	{
		$this->internalName = $name;
	}
}

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', 32), 'Papper\Examples\Priority\UserDTO');

echo "Name proprety: ", $userDTO->name, PHP_EOL;
echo "Name method: ", $userDTO->getName(), PHP_EOL;
