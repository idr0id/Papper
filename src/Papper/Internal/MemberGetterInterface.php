<?php

namespace Papper\Internal;

/**
 * Interface AccessorInterface
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 */
interface MemberGetterInterface
{
	public function getName();

	public function getValue($object);

	public function createNativeCodeTemplate();
}
