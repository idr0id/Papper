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
	private $converter;

	/**
	 * @param ValueConverterInterface|\callable $converter Converter to use
	 * @throws \InvalidArgumentException
	 */
	function __construct($converter)
	{
		if (is_callable($converter)) {
			$converter = new ClosureValueConverter($converter);
		}
		if (!$converter instanceof ValueConverterInterface) {
			throw new \InvalidArgumentException('Converter must be instance of Papper\ValueConverterInterface of callable');
		}
		$this->converter = $converter;
	}

	public function apply(TypeMap $typeMap, PropertyMap $propertyMap = null)
	{
		$propertyMap->setValueConverter($this->converter);
	}
}
