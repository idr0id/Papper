<?php

namespace Papper;

class MapperAlreadyCreatedException extends \Exception
{
	public function __construct($sourceClass, $destinationClass)
	{
		parent::__construct(sprintf('Mapper <%s, %s> already created', $sourceClass, $destinationClass));
	}
} 
