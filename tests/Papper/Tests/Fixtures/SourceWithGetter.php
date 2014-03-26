<?php

namespace Papper\Tests\Fixtures;

class SourceWithGetter extends FixtureBase
{
	private $someValue = 'Some value';

	public function getSomeValue()
	{
		return $this->someValue;
	}
}
