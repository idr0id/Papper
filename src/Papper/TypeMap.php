<?php

namespace Papper;

class TypeMap implements TypeMapInterface
{
	/**
	 * @var string
	 */
	private $destinationType;
	/**
	 * @var string
	 */
	private $sourceType;
	/**
	 * @var ObjectCreatorInterface
	 */
	private $objectCreator;
	/**
	 * @var PropertyMap[]
	 */
	private $propertyMaps = array();
	/**
	 * @var \closure|null
	 */
	private $beforeMapFunc;
	/**
	 * @var \closure|null
	 */
	private $afterMapFunc;

	public function __construct($sourceType, $destinationType, ObjectCreatorInterface $objectCreator)
	{
		$this->destinationType = $destinationType;
		$this->sourceType = $sourceType;
		$this->objectCreator = $objectCreator;
	}

	/**
	 * @return string
	 */
	public function getDestinationType()
	{
		return $this->destinationType;
	}

	/**
	 * @return string
	 */
	public function getSourceType()
	{
		return $this->sourceType;
	}

	/**
	 * @return ObjectCreatorInterface
	 */
	public function getObjectCreator()
	{
		return $this->objectCreator;
	}

	/**
	 * @param ObjectCreatorInterface $objectCreator
	 */
	public function setObjectCreator(ObjectCreatorInterface $objectCreator)
	{
		$this->objectCreator = $objectCreator;
	}

	/**
	 * @return PropertyMap[]
	 */
	public function getPropertyMaps()
	{
		return $this->propertyMaps;
	}

	/**
	 * @return array
	 */
	public function getUnmappedProperties()
	{
		return array_filter($this->propertyMaps, function (PropertyMap $propertyMap) {
			return !$propertyMap->isMapped();
		});
	}

	/**
	 * @param PropertyMap $propertyMap
	 */
	public function addPropertyMap(PropertyMap $propertyMap)
	{
		$this->propertyMaps[$propertyMap->getMemberName()] = $propertyMap;
	}

	/**
	 * @param string $memberName
	 * @return null|PropertyMap
	 */
	public function getPropertyMap($memberName)
	{
		return isset($this->propertyMaps[$memberName]) ? $this->propertyMaps[$memberName] : null;
	}

	/**
	 * @return callable|null
	 */
	public function getBeforeMapFunc()
	{
		return $this->beforeMapFunc;
	}

	/**
	 * @param callable $func
	 */
	public function setBeforeMapFunc(\closure $func)
	{
		$this->beforeMapFunc = $func;
	}

	/**
	 * @return callable|null
	 */
	public function getAfterMapFunc()
	{
		return $this->afterMapFunc;
	}

	/**
	 * @param callable $func
	 */
	public function setAfterMapFunc(\closure $func)
	{
		$this->afterMapFunc = $func;
	}

	/**
	 * @todo validate source members
	 * @todo validate constructor args
	 */
	public function validate()
	{
		$unmappedProperties = $this->getUnmappedProperties();

		if (empty($unmappedProperties)) {
			return;
		}

		$destinationType = $this->destinationType;

		$messages = array_map(function (PropertyMap $propertyMap) use ($destinationType) {
			return sprintf(
				"The following member on <%s::%s> cannot be mapped:\nAdd a custom mapping expression, ignore or modify the destination member.",
				$destinationType,
				$propertyMap->getMemberName()
			);
		}, $unmappedProperties);

		throw new ValidationException(implode("\n\n", $messages));
	}
}
