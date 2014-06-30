<?php

namespace Papper\Internal;

use Papper\ValueConverterInterface;

class ClosureValueConverter implements ValueConverterInterface
{
	private $closure;

	public function __construct(\Closure $closure)
	{
		$this->closure = $closure;
	}

	public function convert($value)
	{
		return call_user_func($this->closure, $value);
	}
}
