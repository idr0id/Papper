<?php

namespace Papper\MemberOption;

use Papper\MemberOptionInterface;
use Papper\PropertyMap;
use Papper\TypeMap;

/**
 * Allows to supply an alternate value for a destination member if the source value is null anywhere along the member chain.
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class NullSubstitute implements MemberOptionInterface
{
	private $nullSubtitute;

	public function __construct($nullSubtitute)
	{
		$this->nullSubtitute = $nullSubtitute;
	}

	public function apply(TypeMap $typeMap, PropertyMap $propertyMap)
	{
		$propertyMap->setNullSubtitute($this->nullSubtitute);
	}
}
