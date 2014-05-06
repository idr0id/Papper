<?php

namespace Papper\Examples\Configure;

use Papper\MappingConfigurationInterface;
use Papper\MappingFluentSyntaxInterface;
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
	public function getSourceType()
	{
		return 'Papper\Examples\Configure\User';
	}

	public function getDestinationType()
	{
		return 'Papper\Examples\Configure\UserDTO';
	}

	public function configure(MappingFluentSyntaxInterface $map)
	{
		$map->forMember('age', new Ignore());
	}
}

Papper::configureMap(new MappingConfiguration());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith'))->toType('Papper\Examples\Configure\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Age: ", $userDTO->age, PHP_EOL;
