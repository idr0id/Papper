<?php

namespace Papper\Tests\Context;

use Papper\Tests\ObjectBase;

class Source extends ObjectBase
{
	public $data;

	public function __construct($data = 'Test data')
	{
		$this->data = $data;
	}
}
