<?php

namespace Papper;

class ConstructedUnexpectedDestinationClass extends \Exception
{
	public function __construct($constructedClass, $expectedClass)
	{
		parent::__construct(sprintf('Constructed class %s, but expected %s', $constructedClass, $expectedClass));
	}
}
