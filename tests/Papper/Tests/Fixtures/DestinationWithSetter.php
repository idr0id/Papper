<?php

namespace Papper\Tests\Fixtures;

class DestinationWithSetter extends FixtureBase
{
	private $someValue;

	public function getSomeValue()
	{
		return $this->someValue;
	}

	public function setSomeValue($someValue)
	{
		$this->someValue = $someValue;
	}
}
