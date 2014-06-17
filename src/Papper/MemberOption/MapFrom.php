<?php

namespace Papper\MemberOption;

use Papper\Internal\Access\ClosureAccessGetter;
use Papper\Internal\Access\PropertyAccessGetter;
use Papper\Internal\MemberGetterInterface;
use Papper\MemberOptionInterface;
use Papper\PropertyMap;
use Papper\TypeMap;

/**
 * Map from a specific source member
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 * @todo add checking of existing of source member
 */
class MapFrom implements MemberOptionInterface
{
	/**
	 * @var MemberGetterInterface
	 */
	private $sourceMemberGetter;

	/**
	 * @param string|\closure $sourceMember
	 * @throws \InvalidArgumentException
	 */
	public function __construct($sourceMember)
	{
		if (is_string($sourceMember)) {
			if (empty($sourceMember)) {
				throw new \InvalidArgumentException('Source member path must not be empty');
			}
			$this->sourceMemberGetter = new PropertyAccessGetter($sourceMember);
		} else if (is_callable($sourceMember)) {
			$this->sourceMemberGetter = new ClosureAccessGetter($sourceMember);
		} else {
			throw new \InvalidArgumentException('Source member should be string (source member path) or closure');
		}
	}

	public function apply(TypeMap $typeMap, PropertyMap $propertyMap)
	{
		$propertyMap->setSourceGetter($this->sourceMemberGetter);
	}
}
