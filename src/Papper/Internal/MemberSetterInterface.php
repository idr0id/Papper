<?php

namespace Papper\Internal;

/**
 * Interface Mutator
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface MemberSetterInterface
{
	public function getName();

	public function setValue($object, $value);
}
