<?php

namespace Papper\NamingConvention;

use Papper\NamingConventionsInterface;

class LowerUnderscoreNamingConvention implements NamingConventionsInterface
{
	/**
	 * Regular expression on how to tokenize a member
	 *
	 * @return string regexp
	 */
	public function getSplittingExpression()
	{
		return '[\p{Ll}0-9]+(?=_?)';
	}

	/**
	 * Character to separate on
	 *
	 * @return string
	 */
	public function getSeparatorCharacter()
	{
		return '_';
	}
}
