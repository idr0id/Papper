<?php

namespace Papper\Tests\Fixtures\Domain;

use Papper\Tests\Fixtures\FixtureBase;

class Order extends FixtureBase
{
	public $id;

	/**
	 * @var Customer of order
	 */
	public $customer;
}
