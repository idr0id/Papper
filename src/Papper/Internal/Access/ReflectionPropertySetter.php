<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberSetterInterface;

class ReflectionPropertySetter implements MemberSetterInterface
{
	private $reflector;

	public function __construct(\ReflectionProperty $reflector)
	{
		$this->reflector = $reflector;
	}

	public function getName()
	{
		return $this->reflector->getName();
	}

	public function setValue($object, $value)
	{
		$this->reflector->setValue($object, $value);
	}

	public function createNativeCodeTemplate()
	{
		return sprintf('$destination->%s = $value', $this->reflector->getName());
	}
}
