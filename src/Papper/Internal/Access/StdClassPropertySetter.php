<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberSetterInterface;

class StdClassPropertySetter implements MemberSetterInterface
{
	public function __construct($propertyName)
	{
		$this->propertyName = $propertyName;
	}

	public function getName()
	{
		return $this->propertyName;
	}

	public function setValue($object, $value)
	{
		$object->{$this->propertyName} = $value;
	}
}
