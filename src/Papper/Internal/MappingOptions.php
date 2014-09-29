<?php

namespace Papper\Internal;

use Papper\MappingOptionsInterface;
use Papper\NamingConvention\PascalCaseNamingConvention;
use Papper\NamingConventionsInterface;

class MappingOptions implements MappingOptionsInterface
{
	/**
	 * @var NamingConventionsInterface
	 */
	private $sourceMemberNamingConvention;
	/**
	 * @var NamingConventionsInterface
	 */
	private $destinationMemberNamingConvention;
	/**
	 * @var string[]
	 */
	private $sourcePrefixes;
	/**
	 * @var string[]
	 */
	private $destinationPrefixes;

	public function __construct()
	{
		$this->sourceMemberNamingConvention = $this->destinationMemberNamingConvention = new PascalCaseNamingConvention();
		$this->sourcePrefixes = array('get');
		$this->destinationPrefixes = array('set');
	}

	public function getSourceMemberNamingConvention()
	{
		return $this->sourceMemberNamingConvention;
	}

	public function setSourceMemberNamingConvention(NamingConventionsInterface $sourceMemberNamingConvention)
	{
		$this->sourceMemberNamingConvention = $sourceMemberNamingConvention;
		return $this;
	}

	public function getDestinationMemberNamingConvention()
	{
		return $this->destinationMemberNamingConvention;
	}

	public function setDestinationMemberNamingConvention(NamingConventionsInterface $destinationMemberNamingConvention)
	{
		$this->destinationMemberNamingConvention = $destinationMemberNamingConvention;
		return $this;
	}

	public function getSourcePrefixes()
	{
		return $this->sourcePrefixes;
	}

	public function setSourcePrefixes(array $sourcePrefixes)
	{
		$this->sourcePrefixes = $sourcePrefixes;
		return $this;
	}

	public function getDestinationPrefixes()
	{
		return $this->destinationPrefixes;
	}

	public function setDestinationPrefixes(array $destinationPrefixes)
	{
		$this->destinationPrefixes = $destinationPrefixes;
		return $this;
	}
}
