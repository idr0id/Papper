<?php

namespace Papper\Internal;

use Papper\Engine;
use Papper\ExecuteMappingFluentSyntaxInterface;
use Papper\MappingContext;
use Papper\MappingException;
use Papper\NotSupportedException;

class ExecuteMappingFluentSyntax implements ExecuteMappingFluentSyntaxInterface
{
	private $context;
	private $engine;

	public function __construct(Engine $engine, $source, $sourceType = null)
	{
		$this->engine = $engine;
		$this->context = new MappingContext();
		$this->context->setSource($source, $sourceType);
	}

	/**
	 * Execute a mapping to the existing destination object.
	 * If no Map exists then one is created.
	 *
	 * @param object|object[] $destination Destination object or collection to map into
	 * @param string|null $destinationType Destination type to map
	 * @return object|object[] The mapped destination object, same instance as the $destination object
	 * @throws NotSupportedException
	 * @throws MappingException
	 */
	public function to($destination, $destinationType = null)
	{
		$this->context->setDestination($destination, $destinationType);
		return $this->engine->execute($this->context);
	}

	/**
	 * Execute a mapping to a new destination object.
	 * If no Map exists then one is created.
	 *
	 * @param string $destinationType Destination type to create and map
	 * @return object|object[]
	 * @throws NotSupportedException
	 * @throws MappingException
	 */
	public function toType($destinationType)
	{
		$this->context->setDestination(null, $destinationType);
		return $this->engine->execute($this->context);
	}
}
