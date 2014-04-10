<?php

namespace Papper;

/**
 * Converts source value to destination value instead of normal member mapping
 *
 * @package Papper
 */
interface ValueConverterInterface
{
	public function convert($value);
}
