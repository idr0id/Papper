<?php

namespace Papper\Examples\ForMember\MapFrom;

use Papper\MemberOption\Ignore;
use Papper\MemberOption\MapFrom;
use Papper\Papper;

require_once __DIR__ . '/../../vendor/autoload.php';

class User
{
	public $name;
	public $lifeTime;

	public function __construct($name, $lifeTime)
	{
		$this->name = $name;
		$this->lifeTime = $lifeTime;
	}
}

class UserDTO
{
	public $name;
	public $age;
}

/** @var UserDTO $userDTO */
$user = new User('John Smith', 32);

Papper::createMap('Papper\Examples\ForMember\MapFrom\User', 'Papper\Examples\ForMember\MapFrom\UserDTO')
	->forMember('age', new MapFrom('lifeTime'));

$userDTO = Papper::map($user, 'Papper\Examples\ForMember\MapFrom\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;
