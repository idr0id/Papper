<?php

namespace Papper\Internal;

use TokenReflection\Broker;
use TokenReflection\Broker\Backend\Memory;

class AnnotationTypeReader
{
	const TYPE_REGEXP = '/^[\\\\\w\d]+/i';
	const PROPERTY_ANNOTATION_NAME = 'var';
	const METHOD_ANNOTATION_NAME = 'method';

	private $broker;

	public function __construct()
	{
		$this->broker = new Broker(new Memory());
	}

	/**
	 * @param \ReflectionProperty|\ReflectionMethod $reflector
	 * @throws \Exception
	 * @throws \TokenReflection\Exception\BrokerException
	 * @throws \TokenReflection\Exception\ParseException
	 * @throws \TokenReflection\Exception\RuntimeException
	 * @return string
	 */
	public function getType($reflector)
	{
		$declaringClass = $reflector->getDeclaringClass();

		$this->broker->processFile($declaringClass->getFileName());
		$class = $this->broker->getClass($declaringClass->name);

		if ($reflector instanceof \ReflectionProperty) {
			$name = 'var';
			$annotations = $class->getProperty($reflector->name)->getAnnotations();
		} elseif ($reflector instanceof \ReflectionMethod) {
			$name = 'return';
			$annotations = $class->getMethod($reflector->name)->getAnnotations();
		} else {
			return null;
		}

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
				list($path, $class) = $this->parseClassPath($matches[0]);

				if (empty($path) && isset($namespaceAliases[$class])) {
					return $namespaceAliases[$class];
				} else if (isset($namespaceAliases[$path])) {
					return $namespaceAliases[$path] . '\\' . $class;
				} else if (class_exists($namespace . '\\' . $class)) {
					return $namespace . '\\' . $class;
				} else if (class_exists($class)) {
					return $class;
				}
			}
		}
		return null;
	}

	private function parseClassPath($classPath)
	{
		$pos = strpos($classPath, "\\");
		return ($pos !== false)
			? array(substr($classPath, 0, $pos), substr($classPath, $pos+1))
			: array('', $classPath);
	}
}
