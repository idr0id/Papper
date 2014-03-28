<?php

namespace Papper\Internal;

use Papper\MappingExpressionInterface;
use Papper\MemberOptionInterface;
use Papper\ObjectCreatorInterface;
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
	 * @inheritdoc
	 */
	public function constructUsing($objectCreator)
	{
		if (is_callable($objectCreator)) {
			$objectCreator = new ClosureObjectCreator($objectCreator);
		}
		if (!$objectCreator instanceof ObjectCreatorInterface) {
			throw new \InvalidArgumentException('Argument objectCreator must be closure or instance of Papper\ObjectCreatorInterface');
		}
		$this->typeMap->setObjectCreator($objectCreator);
	}

	/**
	 * @inheritdoc
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

	/**
	 * @inheritdoc
	 */
	public function beforeMap(\closure $func)
	{
		$this->typeMap->setBeforeMapFunc($func);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function afterMap(\closure $func)
	{
		$this->typeMap->setAfterMapFunc($func);
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
