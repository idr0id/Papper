<?php

namespace Papper\Tests\Mapper;

use Papper\Tests\ObjectBase;

class SourceWithGetter extends ObjectBase
{
	private $someValue = 'Some value';

	public function getSomeValue()
	{
		return $this->someValue;
	}
}
