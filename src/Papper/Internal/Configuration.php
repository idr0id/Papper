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

	public function __construct()
	{
		$this->typeMapFactory = new TypeMapFactory();
		$this->mappingOptions = new MappingOptions();
	}

	public function getMappingOptions()
	{
		return $this->mappingOptions;
	}

	public function findTypeMap($sourceType, $destinationType)
	{
		$sourceType = ltrim($sourceType, '\\');
		$destinationType = ltrim($destinationType, '\\');

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
		return $sourceType . '#' . $destinationType;
	}
}
