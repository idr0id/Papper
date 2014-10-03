<?php

namespace Papper\Internal;

use Papper\MappingOptionsInterface;
use Papper\TypeMap;

/**
 * Class Configuration
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class Configuration
{
	/**
	 * @var TypeMap[]
	 */
	private $typeMaps = array();
	/**
	 * @var TypeMapFactory
	 */
	private $typeMapFactory;
	/**
	 * @var MappingOptionsInterface
	 */
	private $mappingOptions;
	/**
	 * @var MapperFactory
	 */
	private $mapperFactory;

	public function __construct()
	{
		$this->typeMapFactory = new TypeMapFactory();
		$this->mappingOptions = new MappingOptions();
		$this->mapperFactory = new MapperFactory();
	}

	public function getMappingOptions()
	{
		return $this->mappingOptions;
	}

	public function getMapperFactory()
	{
		return $this->mapperFactory;
	}

	public function findTypeMap($sourceType, $destinationType)
	{
		$key = $this->computeTypePairHash($sourceType, $destinationType);
		return isset($this->typeMaps[$key])
			? $this->typeMaps[$key]
			: $this->typeMaps[$key] = $this->typeMapFactory->createTypeMap($sourceType, $destinationType, $this->mappingOptions);
	}

	public function getAllTypeMaps()
	{
		return $this->typeMaps;
	}

	private function computeTypePairHash($sourceType, $destinationType)
	{
		return ltrim($sourceType, '\\') . '#' . ltrim($destinationType, '\\');
	}
}
