<?php

namespace Papper;

/**
 * Interface MemberOptionInterface
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface MemberOptionInterface
{
	public function apply(TypeMap $typeMap, PropertyMap $propertyMap);
}
