<?php

namespace Papper\MemberOption;

use Papper\Internal\ClosureValueConverter;
use Papper\MemberOptionInterface;
use Papper\PropertyMap;
use Papper\TypeMap;
use Papper\ValueConverterInterface;

/**
 * Convert source member using a converter
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
class ConvertUsing implements MemberOptionInterface
{
	/**
	 * @var ValueConverterInterface
	 */
	private $converter;

	/**
	 * @param ValueConverterInterface|\Closure $converter Converter to use
	 * @throws \InvalidArgumentException
	 */
	public function __construct($converter)
	{
		if ($converter instanceof \Closure) {
			$converter = new ClosureValueConverter($converter);
		}
		if (!$converter instanceof ValueConverterInterface) {
			throw new \InvalidArgumentException('Converter must be instance of Papper\ValueConverterInterface of callable');
		}
		$this->converter = $converter;
	}

	public function apply(TypeMap $typeMap, PropertyMap $propertyMap)
	{
		$propertyMap->setValueConverter($this->converter);
	}
}
