<?php

namespace Papper\Tests\Mapper;

use Papper\Tests\ObjectBase;

class DestinationWithSetter extends ObjectBase
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
