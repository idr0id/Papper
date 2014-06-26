<?php

namespace Papper;

/**
 * Mapping configuration options
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface MappingFluentSyntaxInterface
{
	/**
	 * Supply a custom instantiation function for the destination type
	 *
	 * @param ObjectCreatorInterface|\closure $objectCreator Callback to create the destination type given the source object
	 * @return MappingFluentSyntaxInterface
	 */
	public function constructUsing($objectCreator);

	/**
	 * Customize configuration for individual member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions Member option
	 * @return MappingFluentSyntaxInterface
	 */
	public function forMember($name, $memberOptions);

	/**
	 * Create configuration for individual dynamic member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions Member option
	 * @return MappingFluentSyntaxInterface
	 */
	public function forDynamicMember($name, $memberOptions = null);

	/**
	 * Ignores all remaining unmapped members that do not exist on the destination.
	 *
	 * @return $this
	 */
	public function ignoreAllNonExisting();

	/**
	 * Execute a custom closure function to the source and/or destination types before member mapping
	 *
	 * @param \closure $func Callback for the source/destination types
	 * @return $this
	 */
	public function beforeMap(\closure $func);

	/**
	 * Execute a custom function to the source and/or destination types after member mapping
	 *
	 * @param \closure $func Callback for the source/destination types
	 * @return $this
	 */
	public function afterMap(\closure $func);
}
