<?php

namespace Papper\Tests;

class ObjectBase
{
	public static function className()
	{
		return get_called_class();
	}
}
