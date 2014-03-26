<?php

namespace Papper\Internal;

use Papper\ObjectCreatorInterface;

class SimpleObjectCreator implements ObjectCreatorInterface
{
	private $reflector;

	public function __construct(\ReflectionClass $reflector)
	{
		$this->reflector = $reflector;
	}

	public function create($source)
	{
		return $this->reflector->newInstance();
	}
}
