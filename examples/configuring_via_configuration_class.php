<?php

namespace Papper\Examples\Configuring;

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
		$context->createMap('Papper\Examples\Configuring\User', 'Papper\Examples\Configuring\UserDTO')
			->forMember('age', new Ignore());
	}
}

Papper::configureMapping(new MappingConfiguration());

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith'))->toType('Papper\Examples\Configuring\UserDTO');

print_r($userDTO);

/*
 * The above example will output:
 *
 * Papper\Examples\Configuring\UserDTO Object
 * (
 *     [name] => John Smith
 *     [age] => default value of ignored member
 * )
 *
 */
