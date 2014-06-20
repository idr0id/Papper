<?php

namespace Papper\Examples\Flattening;

use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name;
	/**
	 * @var Company
	 */
	public $company;

	public function __construct($name, Company $company)
	{
		$this->name = $name;
		$this->company = $company;
	}
}

class Company
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
	public $companyName;
}

/** @var UserDTO $userDTO */
$userDTO = Papper::map(new User('John Smith', new Company('Acme Corporation')))->toType('Papper\Examples\Flattening\UserDTO');

echo "Name: ", $userDTO->name, PHP_EOL;
echo "Company name: ", $userDTO->companyName, PHP_EOL;
