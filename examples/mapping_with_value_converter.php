<?php

namespace Papper\Examples\MappingWithValueConverter;

use Papper\MemberOption\ConvertUsing;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $age;

	public function __construct(\DateTime $age)
	{
		$this->age = $age;
	}
}

class UserDTO
{
	public $age;
}

$user = new User(new \DateTime('Jan 19, 2009'));

Papper::createMap('Papper\Examples\MappingWithValueConverter\User', 'Papper\Examples\MappingWithValueConverter\UserDTO')
	->forMember('age', new ConvertUsing(function (\DateTime $value) {
		return $value->diff(new \DateTime('Jun 25, 2014'))->format('%y years %m months %d days');
	}));

/** @var UserDTO $userDTO */
$userDTO = Papper::map($user)->toType('Papper\Examples\MappingWithValueConverter\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\MappingWithValueConverter\UserDTO Object
 * (
 *     [age] => 5 years 5 months 6 days
 * )
 *
 */
