<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberGetterInterface;

class ReflectionMethodGetter implements MemberGetterInterface
{
	private $reflector;

	public function __construct(\ReflectionMethod $reflector)
	{
		$this->reflector = $reflector;
	}

	public function getName()
	{
		return $this->reflector->name;
	}

	public function getValue($object)
	{
		return $this->reflector->invoke($object);
	}
}
