<?php

namespace Papper\Internal;

use Papper\TypeMap;

class MapperFactory
{
	private $mappers;

	public function __construct()
	{
		$this->mappers = new \SplObjectStorage();
	}

	public function create(TypeMap $typeMap)
	{
		if (!$this->mappers->contains($typeMap)) {
			$this->mappers->attach($typeMap, new Mapper($typeMap));
		}
		return $this->mappers[$typeMap];
	}
} 
