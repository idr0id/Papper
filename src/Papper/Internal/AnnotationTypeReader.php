<?php

namespace Papper\Internal;

use TokenReflection\Broker;
use TokenReflection\Broker\Backend\Memory;

class AnnotationTypeReader
{
	const TYPE_REGEXP = '#^[\w\d]+#i';
	const PROPERTY_ANNOTATION_NAME = 'var';
	const METHOD_ANNOTATION_NAME = 'method';

	private $broker;

	public function __construct()
	{
		$this->broker = new Broker(new Memory());
	}

	/**
	 * @param \ReflectionProperty|\ReflectionMethod $reflector
	 * @return string
	 */
	public function getType($reflector)
	{
		$declaringClass = $reflector->getDeclaringClass();

		$this->broker->processFile($declaringClass->getFileName());
		$class = $this->broker->getClass($declaringClass->name);

		if ($reflector instanceof \ReflectionProperty) {
			$name = 'var';
		} elseif ($reflector instanceof \ReflectionMethod) {
			$name = 'method';
		} else {
			return null;
		}

		$annotations = $class->getProperty($reflector->name)->getAnnotations();

		if (!isset($annotations[$name])) {
			return null;
		}

		return $this->parseType($annotations[$name], $class->getNamespaceName(), $class->getNamespaceAliases());
	}

	private function parseType($annotations, $namespace, array $namespaceAliases)
	{
		foreach ($annotations as $annotation) {
			preg_match(self::TYPE_REGEXP, $annotation, $matches);
			if (!empty($matches[0])) {
				$class = $matches[0];

				if (isset($namespaceAliases[$class])) {
					return $namespaceAliases[$class];
				}
				if (class_exists($namespace . '\\' . $class)) {
					return $namespace . '\\' . $class;
				}
			}
		}
		return null;
	}
}
