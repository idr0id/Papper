<?php

namespace Papper\Internal\Access;

use Papper\Internal\MemberGetterInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

class PropertyAccessGetter implements MemberGetterInterface
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

	public function getValue($object)
	{
		return PropertyAccess::createPropertyAccessor()->getValue($object, $this->propertyPath);
	}

	public function createNativeCodeTemplate()
	{
		return '{{PropertyMap}}->getSourceGetter()->getValue($source)';
	}
}
