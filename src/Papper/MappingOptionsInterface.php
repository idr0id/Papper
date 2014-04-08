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
	 * @return self
	 */
	public function setSourceMemberNamingConvention(NamingConventionsInterface $sourceMemberNamingConvention);

	/**
	 * Returns naming convention for destination members
	 *
	 * @return NamingConventionsInterface
	 * @return self
	 */
	public function getDestinationMemberNamingConvention();

	/**
	 * Sets naming convention for destination members
	 *
	 * @param NamingConventionsInterface $destinationMemberNamingConvention
	 * @return self
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
	 * @return self
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
	 * @return self
	 */
	public function setDestinationPrefixes(array $destinationPrefixes);
}
