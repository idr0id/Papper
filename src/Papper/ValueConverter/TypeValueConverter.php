<?php

namespace Papper\ValueConverter;

use Papper\ValueConverterException;
use Papper\ValueConverterInterface;

class TypeValueConverter implements ValueConverterInterface
{
	private static $availableTypes = array(
		'boolean',
		'bool',
		'integer',
		'int',
		'float',
		'double',
		'string',
		'array',
		'object',
		'null'
	);

	private $type;

	public function __construct($type)
	{
		if (!in_array($type, self::$availableTypes)) {
			$message = sprintf('The type "%s" does not exist. Available types are: %s', $type, implode(', ', self::$availableTypes));
			throw new ValueConverterException($message);
		}
		$this->type = $type;
	}

	public function convert($value)
	{
		if (!settype($value, $this->type)) {
			throw new ValueConverterException('Converting %s to type ');
		}
		return $value;
	}
}
