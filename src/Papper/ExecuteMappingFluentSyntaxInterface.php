<?php

namespace Papper;

/**
 * Mapping executing options
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface ExecuteMappingFluentSyntaxInterface
{
	/**
	 * Execute a mapping to the existing destination object.
	 * If no Map exists then one is created.
	 *
	 * @param object|object[] $destination Destination object or collection to map into
	 * @param string|null $destinationType Destination type to map
	 * @return object|object[] The mapped destination object, same instance as the $destination object
	 */
	public function to($destination, $destinationType = null);

	/**
	 * Execute a mapping to a new destination object.
	 * If no Map exists then one is created.
	 *
	 * @param string|null $destinationType Destination type to create and map
	 * @return object|object[]
	 */
	public function toType($destinationType);
}
