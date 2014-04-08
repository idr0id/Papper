<?php

namespace Papper;

/**
 * Mapping context
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class MappingContext
{
	private $source;
	private $sourceType;
	private $destination;
	private $destinationType;

	public function getSource()
	{
		return $this->source;
	}

	public function getSourceType()
	{
		return $this->sourceType;
	}

	public function getDestination()
	{
		return $this->destination;
	}

	public function getDestinationType()
	{
		return $this->destinationType;
	}

	public function setSource($source, $sourceType)
	{
		if (!(is_object($source) || is_array($source))) {
			throw new MappingException('Source must be object or array instead of ' . gettype($source));
		}
		if (is_array($source) && empty($sourceType)) {
			throw new MappingException('Source type must explicitly specified for mapping of collection');
		}
		if (is_object($source) && empty($sourceType)) {
			$sourceType = get_class($source);
		}
		$this->source = $source;
		$this->sourceType = $sourceType;
	}

	public function setDestination($destination, $destinationType)
	{
		if ($destination !== null && is_array($this->source)) {
			throw new NotSupportedException('Mapping collection to exists destination is no supported');
		}
		if (is_object($destination) && empty($destinationType)) {
			$destinationType = get_class($destination);
		}
		if (empty($destinationType)) {
			throw new MappingException('Destination type must explicitly specified');
		}
		$this->destination = $destination;
		$this->destinationType = $destinationType;
	}
}
