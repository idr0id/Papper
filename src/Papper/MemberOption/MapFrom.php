<?php

namespace Papper\MemberOption;

use Papper\Internal\Access\PropertyAccessGetter;
use Papper\MemberOptionInterface;
use Papper\PropertyMap;
use Papper\TypeMap;

class MapFrom implements MemberOptionInterface
{
	/**
	 * @var string
	 */
	private $sourceMemberPath;

	/**
	 * @param string $sourceMemberPath
	 * @throws \InvalidArgumentException
	 */
	public function __construct($sourceMemberPath)
	{
		if (empty($sourceMemberPath)) {
			throw new \InvalidArgumentException('Source member path must not be empty');
		}
		$this->sourceMemberPath = $sourceMemberPath;
	}

	public function apply(TypeMap $typeMap, PropertyMap $propertyMap)
	{
		$propertyMap->setSourceGetter(new PropertyAccessGetter($this->sourceMemberPath));
	}
}
