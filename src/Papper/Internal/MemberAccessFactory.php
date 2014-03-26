<?php

namespace Papper\Internal;

use Papper\Internal\Access\ReflectionMethodGetter;
use Papper\Internal\Access\ReflectionMethodSetter;
use Papper\Internal\Access\ReflectionPropertyGetter;
use Papper\Internal\Access\ReflectionPropertySetter;
use Papper\MappingOptionsInterface;
use Papper\NotSupportedException;

class MemberAccessFactory
{
	/**
	 * @param \Reflector $destMember
	 * @return MemberSetterInterface
	 */
	public function createMemberSetter(\Reflector $destMember)
	{
		return $this->createReflectionSetter($destMember);
	}

	/**
	 * @param \ReflectionProperty[]|\ReflectionMethod[] $sourceMembers
	 * @param MappingOptionsInterface $mappingOptions
	 * @return MemberGetterInterface
	 */
	public function createMemberGetter(array $sourceMembers, MappingOptionsInterface $mappingOptions)
	{
		if (count($sourceMembers) == 1) {
			return $this->createReflectionGetter($sourceMembers[0]);
		}

		$propertyPath = implode('.', array_map(function (\Reflector $member) use ($mappingOptions) {
			$memberName = $member->getName();
			foreach ($mappingOptions->getSourcePrefixes() as $prefix) {
				if (stripos($memberName, $prefix) === 0) {
					$withoutPrefix = substr($memberName, strlen($prefix));
					return $withoutPrefix;
				}
			}
			return $memberName;
		}, $sourceMembers));

		return $propertyPath;
	}

	private function createReflectionGetter(\Reflector $reflector)
	{
		if ($reflector instanceof \ReflectionProperty) {
			return new ReflectionPropertyGetter($reflector);
		} else if ($reflector instanceof \ReflectionMethod) {
			return new ReflectionMethodGetter($reflector);
		} else {
			throw new NotSupportedException(sprintf('Reflector %s not supported', get_class($reflector)));
		}
	}

	private function createReflectionSetter(\Reflector $reflector)
	{
		if ($reflector instanceof \ReflectionProperty) {
			return new ReflectionPropertySetter($reflector);
		} else if ($reflector instanceof \ReflectionMethod) {
			return new ReflectionMethodSetter($reflector);
		} else {
			throw new NotSupportedException(sprintf('Reflector %s not supported', get_class($reflector)));
		}
	}
}
