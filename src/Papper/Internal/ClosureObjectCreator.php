<?php

namespace Papper\Internal;

use Papper\ObjectCreatorInterface;

class ClosureObjectCreator implements ObjectCreatorInterface
{
	/**
	 * @var \Closure
	 */
	private $closure;

	public function __construct(\Closure $closure)
	{
		$this->closure = $closure;
	}

	public function create($source)
	{
		return call_user_func($this->closure, $source);
	}
}
