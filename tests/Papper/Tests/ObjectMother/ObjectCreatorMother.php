<?php

namespace Papper\Tests\ObjectMother;

class ObjectCreatorMother
{
	public static function create()
	{
		return \Mockery::mock('Papper\ObjectCreatorInterface');
	}
} 
