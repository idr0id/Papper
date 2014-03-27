<?php

namespace Papper;

/**
 * Interface TypeMapInterface
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface TypeMapInterface
{
	public function getDestinationType();
	public function getSourceType();
	public function getObjectCreator();
	public function setObjectCreator(ObjectCreatorInterface $objectCreator);
	public function getPropertyMaps();
	public function getUnmappedProperties();
	public function addPropertyMap(PropertyMap $propertyMap);
	public function getPropertyMap($memberName);
	public function validate();
}
