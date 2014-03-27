<?php

namespace Papper\Tests\ObjectMother;

class PropertyMapMother
{
	public static function createUnmapped($memberName = 'unmapped')
	{
		$mock = \Mockery::mock('Papper\PropertyMap');
		$mock->shouldReceive('getMemberName')->andReturn($memberName);
		$mock->shouldReceive('isMapped')->andReturn(false);
		return $mock;
	}

	public static function createMapped($memberName = 'mapped')
	{
		$mock = \Mockery::mock('Papper\PropertyMap');
		$mock->shouldReceive('getMemberName')->andReturn($memberName);
		$mock->shouldReceive('isMapped')->andReturn(true);
		return $mock;
	}
}
