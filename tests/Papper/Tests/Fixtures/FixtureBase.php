<?php

namespace Papper\Tests\Fixtures;

class FixtureBase
{
	public static function className()
	{
		return get_called_class();
	}
}
