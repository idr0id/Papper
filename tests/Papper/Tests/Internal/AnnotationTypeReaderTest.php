<?php

namespace Papper\Tests\Internal;

use Papper\Internal\AnnotationTypeReader;
use Papper\Tests\Fixtures\Domain\Customer;
use Papper\Tests\Fixtures\Domain\Order;
use Papper\Tests\Fixtures\Domain\User\User;

class AnnotationTypeReaderTest extends \PHPUnit_Framework_TestCase
{
	public function testTypeOfPropertyAnnotationInSameNamespace()
	{
		// arrange
		$annotationReader = new AnnotationTypeReader();
		$propertyReflector = new \ReflectionProperty(Order::className(), 'customer');

		// act
		$type = $annotationReader->getType($propertyReflector);

		// assert
		$this->assertEquals(Customer::className(), $type);
	}

	public function testTypeOfMethodAnnotationInAnotherNamespace()
	{
		// arrange
		$annotationReader = new AnnotationTypeReader();
		$propertyReflector = new \ReflectionProperty(Customer::className(), 'user');

		// act
		$type = $annotationReader->getType($propertyReflector);

		// assert
		$this->assertEquals(User::className(), $type);
	}
}
