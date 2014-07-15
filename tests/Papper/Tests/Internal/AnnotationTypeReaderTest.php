<?php

namespace Papper\Tests\Internal;

use Papper\Internal\AnnotationTypeReader;
use Papper\Tests\Internal\AnnotationFixtures\AnotherNS\AliasedClass as AliasedPathClass;
use Papper\Tests\Internal\AnnotationFixtures\AnotherNS\AnotherNsClass;
use Papper\Tests\Internal\AnnotationFixtures\SameNS\AliasedClass;
use Papper\Tests\Internal\AnnotationFixtures\SameNS\Composite;
use Papper\Tests\Internal\AnnotationFixtures\SameNS\SameNsClass;

include 'AnnotationFixtures/PapperGlobalAnnotationClass.php';

class AnnotationTypeReaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider testGetTypeDataProvider
	 * @param \Reflector $reflector
	 * @param string $classname
	 */
	public function testGetType($reflector, $classname)
	{
		// arrange
		$annotationReader = new AnnotationTypeReader();
		// act
		$type = $annotationReader->getType($reflector);
		// assert
		$this->assertEquals($classname, $type);
	}

	public function testGetTypeDataProvider()
	{
		return array(
			'method, another ns' => array(
				new \ReflectionMethod(Composite::className(), 'getAnotherNsClass'), AnotherNsClass::className()
			),
			'property, same ns' => array(
				new \ReflectionProperty(Composite::className(), 'sameNsClass'), SameNsClass::className()
			),
			'property, aliased class' => array(
				new \ReflectionProperty(Composite::className(), 'aliasedClass'), AliasedClass::className()
			),
			'property, aliased path class' => array(
				new \ReflectionProperty(Composite::className(), 'aliasedPathClass'), AliasedPathClass::className()
			),
			'property, global class' => array(
				new \ReflectionProperty(Composite::className(), 'globalClass'), \PapperGlobalAnnotationClass::className()
			),
		);
	}
}
