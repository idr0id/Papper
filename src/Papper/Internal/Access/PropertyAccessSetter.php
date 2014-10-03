<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberSetterInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

class PropertyAccessSetter implements MemberSetterInterface
{
	private $propertyPathAsString;
	private $propertyPath;

	public function __construct($propertyPathAsString)
	{
		$this->propertyPathAsString = $propertyPathAsString;
		$this->propertyPath = new PropertyPath($propertyPathAsString);
	}

	public function getName()
	{
		return $this->propertyPathAsString;
	}

	public function setValue($object, $value)
	{
		PropertyAccess::createPropertyAccessor()->setValue($object, $this->propertyPath, $value);
	}

	public function createNativeCodeTemplate()
	{
		return '{{PropertyMap}}->getDestinationSetter()->setValue($destination, $value)';
	}
}
