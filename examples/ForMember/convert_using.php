<?php

namespace Papper\Examples\ForMember\ConvertUsing;

use Papper\MemberOption\ConvertUsing;
use Papper\Papper;

require_once __DIR__ . '/../../vendor/autoload.php';

class User
{
	public $name;
	public $age;

	public function __construct($name, \DateTime $age)
	{
		$this->name = $name;
		$this->age = $age;
	}
}

class UserDTO
{
	public $name;
	public $age;
}

/** @var UserDTO $userDTO */
$user = new User('John Smith', new \DateTime('now - 25 year'));

Papper::createMap('Papper\Examples\ForMember\ConvertUsing\User', 'Papper\Examples\ForMember\ConvertUsing\UserDTO')
	->forMember('age', new ConvertUsing(function (\DateTime $value) {
		$diff = $value->diff(new \DateTime());
		return $diff->format('%y');
	}));

$userDTO = Papper::map($user, 'Papper\Examples\ForMember\ConvertUsing\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;

