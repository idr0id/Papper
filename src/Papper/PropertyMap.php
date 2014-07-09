<?php

namespace Papper;

use Papper\Internal\MemberGetterInterface;
use Papper\Internal\MemberSetterInterface;

class PropertyMap
{
	/**
	 * @var MemberGetterInterface
	 */
	private $sourceGetter;
	/**
	 * @var MemberSetterInterface
	 */
	private $destinationSetter;
	/**
	 * @var ValueConverterInterface
	 */
	private $valueConverter = null;
	/**
	 * @var bool
	 */
	private $isIgnored = false;
	/**
	 * @var mixed
	 */
	private $nullSubtitute = null;

	public function __construct(MemberSetterInterface $setter, MemberGetterInterface $getter = null)
	{
		$this->destinationSetter = $setter;
		$this->sourceGetter = $getter;
	}

	public function getMemberName()
	{
		return $this->destinationSetter->getName();
	}

	public function getDestinationSetter()
	{
		return $this->destinationSetter;
	}

	public function getSourceGetter()
	{
		return $this->sourceGetter;
	}

	public function setSourceGetter(MemberGetterInterface $sourceGetter)
	{
		$this->sourceGetter = $sourceGetter;
	}

	public function getValueConverter()
	{
		return $this->valueConverter;
	}

	public function setValueConverter(ValueConverterInterface $valueConverter)
	{
		$this->valueConverter = $valueConverter;
	}

	public function hasValueConverter()
	{
		return $this->valueConverter !== null;
	}

	public function getNullSubtitute()
	{
		return $this->nullSubtitute;
	}

	public function setNullSubtitute($nullSubtitute)
	{
		$this->nullSubtitute = $nullSubtitute;
	}

	public function ignore()
	{
		$this->isIgnored = true;
	}

	public function isIgnored()
	{
		return $this->isIgnored;
	}

	public function isMapped()
	{
		return $this->isIgnored || $this->sourceGetter !== null;
	}
}
