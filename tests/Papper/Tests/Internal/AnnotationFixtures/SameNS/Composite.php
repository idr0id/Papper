<?php

namespace Papper\Tests\Internal\AnnotationFixtures\SameNS;

use Papper\Tests\FixtureBase;
use Papper\Tests\Internal\AnnotationFixtures\AnotherNS;
use Papper\Tests\Internal\AnnotationFixtures\AnotherNS\AnotherNsClass;
use Papper\Tests\Internal\AnnotationFixtures\SameNS\AliasedClass as ThisIsAliasedClass;

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
