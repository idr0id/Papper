<?php

namespace Papper\Tests;

use Papper\Engine;

class TestCaseBase extends \PHPUnit_Framework_TestCase
{
	protected function createEngine()
	{
		return new Engine();
	}
}
