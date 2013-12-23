<?php

namespace Papper\Tests\Mapper;

use Papper\Tests\ObjectBase;

class DestinationWithConstructor extends ObjectBase
{
	private $someValue;

	public function __construct($someValue)
	{
		$this->someValue = $someValue;
	}

	public function getSomeValue()
	{
		return $this->someValue;
	}
}
