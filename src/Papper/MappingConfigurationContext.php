<?php

namespace Papper;

use Papper\FluentSyntax\MappingFluentSyntax;

class MappingConfigurationContext
{
	private $engine;

	public function __construct(Engine $engine)
	{
		$this->engine = $engine;
	}

	/**
	 * Creates a TypeMap for the source's type and destination's type.
	 *
	 * @param string $sourceType Source type
	 * @param string $destinationType Destination type
	 * @throws ClassNotFoundException
	 * @return MappingFluentSyntax
	 */
	public function createMap($sourceType, $destinationType)
	{
		return $this->engine->createMap($sourceType, $destinationType);
	}
}
