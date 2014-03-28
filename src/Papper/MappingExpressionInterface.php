<?php

namespace Papper;

/**
 * Mapping configuration options
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface MappingExpressionInterface
{
	/**
	 * Supply a custom instantiation function for the destination type
	 *
	 * @param ObjectCreatorInterface|\closure $objectCreator Callback to create the destination type given the source object
	 * @return MappingExpressionInterface
	 */
	public function constructUsing($objectCreator);

	/**
	 * Customize configuration for individual member
	 *
	 * @param string $name Destination member name
	 * @param MemberOptionInterface|MemberOptionInterface[] $memberOptions Member option
	 * @return MappingExpressionInterface
	 */
	public function forMember($name, $memberOptions);

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
