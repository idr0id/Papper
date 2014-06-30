<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberGetterInterface;

class ClosureAccessGetter implements MemberGetterInterface
{
	private $closure;

	public function __construct(\Closure $closure)
	{
		$this->closure = $closure;
	}

	public function getName()
	{
		return null;
	}

	public function getValue($object)
	{
		return call_user_func($this->closure, $object);
	}
}
