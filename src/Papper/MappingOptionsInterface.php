<?php

namespace Papper;

/**
 * Options for matching source/destination member types
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 * @todo aliased members
 * @todo postfixes
 * @todo allow mapping to constructors that accept arguments
 */
interface MappingOptionsInterface
{
	/**
	 * Returns naming convention for source members
	 *
	 * @return NamingConventionsInterface
	 */
	public function getSourceMemberNamingConvention();

	/**
	 * Sets naming convention for source members
	 *
	 * @param NamingConventionsInterface $sourceMemberNamingConvention
	 */
	public function setSourceMemberNamingConvention(NamingConventionsInterface $sourceMemberNamingConvention);

	/**
	 * Returns naming convention for destination members
	 *
	 * @return NamingConventionsInterface
	 */
	public function getDestinationMemberNamingConvention();

	/**
	 * Sets naming convention for destination members
	 *
	 * @param NamingConventionsInterface $destinationMemberNamingConvention
	 */
	public function setDestinationMemberNamingConvention(NamingConventionsInterface $destinationMemberNamingConvention);

	/**
	 * Returns source member name prefixes to ignore/drop
	 *
	 * @return string[]
	 */
	public function getSourcePrefixes();

	/**
	 * Sets source member name prefixes to ignore/drop
	 *
	 * @param string[] $sourcePrefixes
	 */
	public function setSourcePrefixes(array $sourcePrefixes);

	/**
	 * Returns Destination member name prefixes to ignore/drop
	 *
	 * @return string[]
	 */
	public function getDestinationPrefixes();

	/**
	 * Sets destination member name prefixes to ignore/drop
	 *
	 * @param string[] $destinationPrefixes
	 */
	public function setDestinationPrefixes(array $destinationPrefixes);
}
