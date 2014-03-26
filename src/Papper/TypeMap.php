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

	public function __construct($sourceType, $destinationType, ObjectCreatorInterface $objectCreator)
	{
		$this->destinationType = $destinationType;
		$this->sourceType = $sourceType;
		$this->objectCreator = $objectCreator;
	}

	public function getDestinationType()
	{
		return $this->destinationType;
	}

	public function getSourceType()
	{
		return $this->sourceType;
	}

	public function getObjectCreator()
	{
		return $this->objectCreator;
	}

	public function setObjectCreator(ObjectCreatorInterface $objectCreator)
	{
		$this->objectCreator = $objectCreator;
	}

	public function getPropertyMaps()
	{
		return $this->propertyMaps;
	}

	public function getUnmappedProperties()
	{
		return array_filter($this->propertyMaps, function (PropertyMap $propertyMap) {
			return !$propertyMap->isMapped();
		});
	}

	public function addPropertyMap(PropertyMap $propertyMap)
	{
		$this->propertyMaps[$propertyMap->getMemberName()] = $propertyMap;
	}

	public function getPropertyMap($memberName)
	{
		return isset($this->propertyMaps[$memberName]) ? $this->propertyMaps[$memberName] : null;
	}

	public function validate()
	{
		// @todo validate source members
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
