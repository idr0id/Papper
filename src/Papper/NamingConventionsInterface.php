<?php

namespace Papper;

/**
 * Defines a naming convention strategy
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface NamingConventionsInterface
{
	/**
	 * Regular expression on how to tokenize a member
	 *
	 * @return string regexp
	 */
	public function getSplittingExpression();

	/**
	 * Character to separate on
	 *
	 * @return string
	 */
	public function getSeparatorCharacter();
}
