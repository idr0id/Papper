<?php

namespace Papper\Internal\Convention;

use Papper\NamingConventionsInterface;

class PascalCaseNamingConvention implements NamingConventionsInterface
{
	/**
	 * Regular expression on how to tokenize a member
	 *
	 * @return string regexp
	 */
	public function getSplittingExpression()
	{
		return '(\p{Lu}+(?=$|\p{Lu}[\p{Ll}0-9])|\p{Lu}?[\p{Ll}0-9]+)';
	}

	/**
	 * Character to separate on
	 *
	 * @return string
	 */
	public function getSeparatorCharacter()
	{
		return '';
	}
}
