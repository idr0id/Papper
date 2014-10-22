<?php

namespace Papper\FluentSyntax;

use Papper\Internal\Access\PropertyAccessSetter;
use Papper\Internal\Access\StdClassPropertySetter;
use Papper\Internal\ClosureObjectCreator;
use Papper\MemberOptionInterface;
use Papper\ObjectCreatorInterface;
use Papper\PapperConfigurationException;
use Papper\PropertyMap;
use Papper\TypeMap;

/**
 * Mapping configuration options
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class MappingFluentSyntax
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
	 * Supply a custom instantiation function for the destination type
	 *
	 * @param ObjectCreatorInterface|\Closure $objectCreator Callback to create the destination type given the source object
	 * @throws PapperConfigurationException
	 * @return MappingFluentSyntax
	 */
	public function constructUsing($objectCreator)
	{
		if ($objectCreator instanceof \Closure) {
			$objectCreator = new ClosureObjectCreator($objectCreator);
		}
		if (!$objectCreator instanceof ObjectCreatorInterface) {
			throw new PapperConfigurationException('Argument objectCreator must be closure or instance of Papper\ObjectCreatorInterface');
		}
		$this->typeMap->setObjectCreator($objectCreator);
	}

	/**
	 * Customize configuration for individual member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions Member option
	 * @throws PapperConfigurationException
	 * @return MappingFluentSyntax
	 */
	public function forMember($name, $memberOptions)
	{
		$propertyMap = $this->typeMap->getPropertyMap($name);
		if ($propertyMap === null) {
			throw new PapperConfigurationException(sprintf('Unable to find destination member %s on type %s', $name, $this->typeMap->getDestinationType()));
		}
		$memberOptions = is_array($memberOptions) ? $memberOptions : array($memberOptions);
		foreach ($memberOptions as $memberOption) {
			if (!$memberOption instanceof MemberOptionInterface) {
				throw new PapperConfigurationException('Member options must be array or instance of Papper\MemberOptionInterface');
			}
			$memberOption->apply($this->typeMap, $propertyMap);
		}
		return $this;
	}

	/**
	 * Create configuration for individual dynamic member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions Member option
	 * @throws PapperConfigurationException
	 * @return MappingFluentSyntax
	 */
	public function forDynamicMember($name, $memberOptions = null)
	{
		$propertyMap = $this->typeMap->getPropertyMap($name);
		if ($propertyMap !== null) {
			throw new PapperConfigurationException(sprintf('Unable to create dynamic destination member %s on type %s because it already exists in type', $name, $this->typeMap->getDestinationType()));
		}

		$setter = $this->typeMap->getDestinationType() === 'stdClass'
			? new StdClassPropertySetter($name)
			: new PropertyAccessSetter($name);

		$this->typeMap->addPropertyMap($propertyMap = new PropertyMap($setter));

		$memberOptions = is_array($memberOptions) ? $memberOptions : array($memberOptions);
		foreach ($memberOptions as $memberOption) {
			if (!$memberOption instanceof MemberOptionInterface) {
				throw new PapperConfigurationException('Member options must be array or instance of Papper\MemberOptionInterface');
			}
			$memberOption->apply($this->typeMap, $propertyMap);
		}
		return $this;
	}

	/**
	 * Ignores all remaining unmapped members that do not exist on the destination.
	 *
	 * @return $this
	 */
	public function ignoreAllNonExisting()
	{
		foreach ($this->typeMap->getUnmappedPropertyMaps() as $propertyMap) {
			$propertyMap->ignore();
		}
		return $this;
	}

	/**
	 * Execute a custom closure function to the source and/or destination types before member mapping
	 *
	 * @param \Closure $func Callback for the source/destination types
	 * @return $this
	 */
	public function beforeMap(\Closure $func)
	{
		$this->typeMap->setBeforeMapFunc($func);
		return $this;
	}

	/**
	 * Execute a custom function to the source and/or destination types after member mapping
	 *
	 * @param \Closure $func Callback for the source/destination types
	 * @return $this
	 */
	public function afterMap(\Closure $func)
	{
		$this->typeMap->setAfterMapFunc($func);
		return $this;
	}
}
