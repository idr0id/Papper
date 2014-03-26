<?php

namespace Papper\Internal;

use Papper\MappingExpressionInterface;
use Papper\MemberOptionInterface;
use Papper\TypeMap;

/**
 * Mapping configuration options
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class MappingExpression implements MappingExpressionInterface
{
	/**
	 * @var TypeMap
	 */
	private $typeMap;

	public function __construct(TypeMap $typeMap)
	{
		$this->typeMap = $typeMap;
	}

	/**
	 * Customize configuration for individual member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions member option
	 * @return MappingExpressionInterface
	 */
	public function forMember($name, $memberOptions)
	{
		/** @var $memberOptions MemberOptionInterface[] */
		$memberOptions = is_array($memberOptions) ? $memberOptions : array($memberOptions);
		$this->assertMemberOptions($memberOptions);

		$propertyMap = $this->typeMap->getPropertyMap($name);

		foreach ($memberOptions as $memberOption) {
			$memberOption->apply($this->typeMap, $propertyMap);
		}

		return $this;
	}

	private function assertMemberOptions(array $memberOptions)
	{
		foreach ($memberOptions as $memberOption) {
			if (!$memberOption instanceof MemberOptionInterface) {
				throw new \InvalidArgumentException('Argument memberOptions must be array or single instance of Papper\MemberOptionInterface');
			}
		}
	}
}

