<?php

namespace Papper;

class ClassNotFoundException extends ContextException
{
	public function __construct($class)
	{
		parent::__construct(sprintf('Class %s not found', $class));
	}
}
