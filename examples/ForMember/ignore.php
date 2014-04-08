<?php

namespace Papper\Examples\ForMember\Ignore;

use Papper\MemberOption\Ignore;
use Papper\Papper;

require_once __DIR__ . '/../../vendor/autoload.php';

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
	public $age = "default value of ignored member";
}

/** @var UserDTO $userDTO */
$user = new User('John Smith');

Papper::createMap('Papper\Examples\ForMember\Ignore\User', 'Papper\Examples\ForMember\Ignore\UserDTO')
	->forMember('age', new Ignore());

$userDTO = Papper::map($user)->toType('Papper\Examples\ForMember\Ignore\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;
