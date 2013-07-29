<?php

namespace Papper;

class MapperNotFoundException extends \Exception
{
	public function __construct($sourceClass, $destinationClass)
	{
		parent::__construct(sprintf('Mapper <%s, %s> not found', $sourceClass, $destinationClass));
	}
}
