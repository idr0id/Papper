<?php

namespace Papper;

use Exception;

class ClassNotFoundException extends \Exception
{
	public function __construct($class)
	{
		parent::__construct(sprintf('Class %s not found', $class));
	}
}
