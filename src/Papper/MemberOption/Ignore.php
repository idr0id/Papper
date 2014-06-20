<?php

namespace Papper\MemberOption;

use Papper\MemberOptionInterface;
use Papper\PropertyMap;
use Papper\TypeMap;

/**
 * Ignore this member for configuration validation and skip during mapping
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class Ignore implements MemberOptionInterface
{
	public function apply(TypeMap $typeMap, PropertyMap $propertyMap)
	{
		$propertyMap->ignore();
	}
}
