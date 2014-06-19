<?php

namespace Papper\Internal;

use TokenReflection\Broker;
use TokenReflection\Broker\Backend\Memory;
use TokenReflection\ReflectionClass as TokenReflectionClass;

/**
 * Class AnnotationTypeReader
 *
 * @author Vladimir Komissarov <dr0id@dr0id.ru>
 * @todo add detection of multiple classes definition
 * @todo refactor returned type from string to reflection class
 */
class AnnotationTypeReader
{
	const TYPE_REGEXP = '/^[\\\\a-zA-Z_\x7f-\xff][\\\\a-zA-Z0-9_\x7f-\xff]*/i';
	const PROPERTY_ANNOTATION_NAME = 'var';
	const METHOD_ANNOTATION_NAME = 'return';

	/**
	 * @var string[]
	 */
	private static $ignoredTypes = array('boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array', 'object', 'null');

	/**
	 * @var \TokenReflection\Broker
	 */
	private $broker;

	public function __construct()
	{
		$this->broker = new Broker(new Memory());
	}

	public function getType(\Reflector $reflector)
	{
		if (!$reflector instanceof \ReflectionProperty && !$reflector instanceof \ReflectionMethod) {
			throw new \InvalidArgumentException('Argument "reflector" should be instance of \ReflectionProperty or \ReflectionMethod');
		}

		if (null === $tokenizedClass = $this->getTokenizedReflectionClass($reflector->getDeclaringClass())) {
			return null;
		}

		if ($reflector instanceof \ReflectionProperty) {
			$name = self::PROPERTY_ANNOTATION_NAME;
			$annotations = $tokenizedClass->getProperty($reflector->name)->getAnnotations();
		} else {
			$name = self::METHOD_ANNOTATION_NAME;
			$annotations = $tokenizedClass->getMethod($reflector->name)->getAnnotations();
		}

		return isset($annotations[$name])
			? $this->parseType($annotations[$name], $tokenizedClass)
			: null;
	}

	private function getTokenizedReflectionClass(\ReflectionClass $class)
	{
		$this->broker->processFile($class->getFileName());
		return $this->broker->getClass($class->name);
	}

	private function parseType($annotations, TokenReflectionClass $tokenizedClass)
	{
		$currentNamespace = $tokenizedClass->getNamespaceName();
		$currentNamespaceAliases = $tokenizedClass->getNamespaceAliases();

		foreach ($annotations as $annotation) {
			preg_match(self::TYPE_REGEXP, $annotation, $matches);
			if (!empty($matches[0])) {
				list($parsedNamespace, $parsedClassName) = $this->parseClass($matches[0]);

				if ($this->isIgnoredType($parsedClassName)) {
					continue;
				}

				if (empty($parsedNamespace) && isset($currentNamespaceAliases[$parsedClassName])) {
					return $currentNamespaceAliases[$parsedClassName];
				} else if (isset($currentNamespaceAliases[$parsedNamespace])) {
					return $currentNamespaceAliases[$parsedNamespace] . '\\' . $parsedClassName;
				} else if (class_exists($currentNamespace . '\\' . $parsedClassName)) {
					return $currentNamespace . '\\' . $parsedClassName;
				} else if (class_exists($parsedClassName)) {
					return $parsedClassName;
				}
			}
		}
		return null;
	}

	private function parseClass($classDefinition)
	{
		return (false !== $pos = strpos($classDefinition, "\\"))
			? array(substr($classDefinition, 0, $pos), substr($classDefinition, $pos + 1))
			: array('', $classDefinition);
	}

	private function isIgnoredType($type)
	{
		return in_array($type, self::$ignoredTypes);
	}
}
