<?php

namespace Papper\Tests\Fixtures;

class DestinationWithConstructor extends FixtureBase
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
