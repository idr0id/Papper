<?php

namespace Papper\Internal;

use Papper\TypeMap;
use Papper\ValidationException;

class Mapper implements MapperInterface
{
	private $objectCreator;
	private $propertyMaps;
	private $sourceType;
	private $destinationType;
	private $beforeMapFunc;
	private $afterMapFunc;

	public function __construct(TypeMap $typeMap)
	{
		$this->objectCreator = $typeMap->getObjectCreator();
		$this->propertyMaps = $typeMap->getMappedPropertyMaps();
		$this->sourceType = $typeMap->getSourceType();
		$this->destinationType = $typeMap->getDestinationType();
		$this->beforeMapFunc = $typeMap->getBeforeMapFunc();
		$this->afterMapFunc = $typeMap->getAfterMapFunc();
	}

	public function map($source, $destination = null)
	{
		if ($destination === null) {
			$destination = $this->objectCreator->create($source);
		}

		if (!$destination instanceof $this->destinationType) {
			$type = is_object($destination) ? get_class($destination) : gettype($destination);
			$message = sprintf('Constructed object type expected %s, but actual %s', $destinationType, $type);
			throw new ValidationException($message);
		}

		if (!$source instanceof $this->sourceType) {
			$type = is_object($source) ? get_class($source) : gettype($source);
			$message = sprintf('Source object type expected %s, but actual %s', $this->destinationType, $type);
			throw new ValidationException($message);
		}

		if ($this->beforeMapFunc) {
			call_user_func($this->beforeMapFunc, array($source, $destination));
		}

		foreach ($this->propertyMaps as $propertyMap) {
			$value = $propertyMap->getSourceGetter()->getValue($source);
			if ($propertyMap->hasValueConverter()) {
				$value = $propertyMap->getValueConverter()->convert($value);
			}
			if ($value === null) {
				$value = $propertyMap->getNullSubtitute();
			}
			$propertyMap->getDestinationSetter()->setValue($destination, $value);
		}

		if ($this->afterMapFunc) {
			call_user_func($this->afterMapFunc, array($source, $destination));
		}

		return $destination;
	}
}
