<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberGetterInterface;

class ReflectionPropertyGetter implements MemberGetterInterface
{
	private $reflector;

	public function __construct(\ReflectionProperty $reflector)
	{
		$this->reflector = $reflector;
	}

	public function getName()
	{
		return $this->reflector->name;
	}

	public function getValue($object)
	{
		return $this->reflector->getValue($object);
	}
}
