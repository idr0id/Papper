<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberSetterInterface;

class ReflectionMethodSetter implements MemberSetterInterface
{
	private $reflector;

	public function __construct(\ReflectionMethod $reflector)
	{
		$this->reflector = $reflector;
	}

	public function getName()
	{
		return $this->reflector->name . '()';
	}

	public function setValue($object, $value)
	{
		$this->reflector->invokeArgs($object, array($value));
	}

	public function createNativeCodeTemplate()
	{
		return sprintf('$destination->%s($value)', $this->reflector->getName());
	}
}
