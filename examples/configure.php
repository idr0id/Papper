<?php

namespace Papper\Examples\Configure;

use Papper\MappingConfigurationContext;
use Papper\MappingConfigurationInterface;
use Papper\MemberOption\Ignore;
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
	public $age = "default value of ignored member";
}

class MappingConfiguration implements MappingConfigurationInterface
{
	public function configure(MappingConfigurationContext $context)
	{
		$context->createMap('Papper\Examples\Configure\User', 'Papper\Examples\Configure\UserDTO')
			->forMember('age', new Ignore());
	}
}

Papper::configureMapping(new MappingConfiguration());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith'))->toType('Papper\Examples\Configure\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;
