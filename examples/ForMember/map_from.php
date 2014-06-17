<?php

namespace Papper\Examples\ForMember\MapFrom;

use Papper\MemberOption\MapFrom;
use Papper\Papper;

require_once __DIR__ . '/../../vendor/autoload.php';

class User
{
	public $name;
	public $family;
	public $lifeTime;

	public function __construct($name, $family, $lifeTime)
	{
		$this->name = $name;
		$this->family = $family;
		$this->lifeTime = $lifeTime;
	}
}

class UserDTO
{
	public $name;
	public $age;
}

/** @var UserDTO $userDTO */
$user = new User('John', 'Smith', 32);

Papper::createMap('Papper\Examples\ForMember\MapFrom\User', 'Papper\Examples\ForMember\MapFrom\UserDTO')
	->forMember('name', new MapFrom(function(User $source) {
		return $source->family . ' ' . $source->name;
	}))
	->forMember('age', new MapFrom('lifeTime'));

$userDTO = Papper::map($user)->toType('Papper\Examples\ForMember\MapFrom\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;
