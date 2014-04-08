<?php

namespace Papper\Examples\MapToExistsObject;

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
	private $id;
	public $name;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}
}

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', 32))->to(new UserDTO(123));

echo "Id: ", $userDTO->getId(), PHP_EOL;
echo "Name: ", $userDTO->name, PHP_EOL;
