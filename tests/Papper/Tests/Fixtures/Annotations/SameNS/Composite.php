<?php

namespace Papper\Tests\Fixtures\Annotations\SameNS;

use Papper\Tests\Fixtures\Annotations\AnotherNS;
use Papper\Tests\Fixtures\Annotations\AnotherNS\AnotherNsClass;
use Papper\Tests\Fixtures\Annotations\SameNS\AliasedClass as ThisIsAliasedClass;
use Papper\Tests\Fixtures\FixtureBase;

class Composite extends FixtureBase
{
	/**
	 * @var \PapperGlobalAnnotationClass
	 */
	public $globalClass;

	/**
	 * @var SameNsClass
	 */
	public $sameNsClass;

	/**
	 * @var ThisIsAliasedClass
	 */
	public $aliasedClass;

	/**
	 * @var AnotherNS\AliasedClass
	 */
	public $aliasedPathClass;

	/**
	 * @return AnotherNsClass
	 */
	public function getAnotherNsClass()
	{
	}
}
