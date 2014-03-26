<?php

namespace Papper\Internal;

use Papper\ValueConverterInterface;

class ClosureValueConverter implements ValueConverterInterface
{
	private $closure;

	public function __construct(\closure $closure)
	{
		$this->closure = $closure;
	}

	public function converter($value)
	{
		$closure = $this->closure;
		return $closure($value);
	}
}
