<?php

namespace Papper\Internal;

use Papper\Internal\Access\PropertyAccessSetter;
use Papper\Internal\Access\StdClassPropertySetter;
use Papper\MappingFluentSyntaxInterface;
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
class MappingFluentSyntax implements MappingFluentSyntaxInterface
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
		if ($objectCreator instanceof \Closure) {
			$objectCreator = new ClosureObjectCreator($objectCreator);
		}
		if (!$objectCreator instanceof ObjectCreatorInterface) {
			throw new PapperConfigurationException('Argument objectCreator must be closure or instance of Papper\ObjectCreatorInterface');
		}
		$this->typeMap->setObjectCreator($objectCreator);
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
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
	 * @inheritdoc
	 */
	public function ignoreAllNonExisting()
	{
		foreach ($this->typeMap->getUnmappedPropertyMaps() as $propertyMap) {
			$propertyMap->ignore();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function beforeMap(\Closure $func)
	{
		$this->typeMap->setBeforeMapFunc($func);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function afterMap(\Closure $func)
	{
		$this->typeMap->setAfterMapFunc($func);
		return $this;
	}
}
