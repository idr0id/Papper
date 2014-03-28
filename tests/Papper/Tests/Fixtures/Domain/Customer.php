<?php

namespace Papper\Tests\Fixtures\Domain;

use Papper\Tests\Fixtures\Domain\User\User;
use Papper\Tests\Fixtures\FixtureBase;

class Customer extends FixtureBase
{
	public $name;

	/**
	 * @var User
	 */
	public $user;
}
