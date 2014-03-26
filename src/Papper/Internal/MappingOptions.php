<?php

namespace Papper\Internal;

use Papper\Internal\Convention\PascalCaseNamingConvention;
use Papper\MappingOptionsInterface;
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
	}

	public function getDestinationMemberNamingConvention()
	{
		return $this->destinationMemberNamingConvention;
	}

	public function setDestinationMemberNamingConvention(NamingConventionsInterface $destinationMemberNamingConvention)
	{
		$this->destinationMemberNamingConvention = $destinationMemberNamingConvention;
	}

	public function getSourcePrefixes()
	{
		return $this->sourcePrefixes;
	}

	public function setSourcePrefixes(array $sourcePrefixes)
	{
		$this->sourcePrefixes = $sourcePrefixes;
	}

	public function getDestinationPrefixes()
	{
		return $this->destinationPrefixes;
	}

	public function setDestinationPrefixes(array $destinationPrefixes)
	{
		$this->destinationPrefixes = $destinationPrefixes;
	}
}