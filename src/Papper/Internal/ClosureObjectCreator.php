<?php

namespace Papper\Internal;

use Papper\ObjectCreatorInterface;

class ClosureObjectCreator implements ObjectCreatorInterface
{
	private $objectCreatorFunc;

	public function __construct(\closure $objectCreatorFunc)
	{
		$this->objectCreatorFunc = $objectCreatorFunc;
	}

	public function create($source)
	{
		$closure = $this->objectCreatorFunc;
		return $closure($source);
	}
}
