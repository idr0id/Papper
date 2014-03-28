<?php

namespace Papper\Internal;

use Papper\ClassNotFoundException;
use Papper\MappingOptionsInterface;
use Papper\PropertyMap;
use Papper\TypeMap;

class TypeMapFactory
{
	/**
	 * @var \ReflectionClass[]
	 */
	private $reflectorsCache = array();
	/**
	 * @var MemberAccessFactory
	 */
	private $memberAccessFactory;
	/**
	 * @var AnnotationTypeReader
	 */
	private $annotationTypeReader;

	public function __construct()
	{
		$this->memberAccessFactory = new MemberAccessFactory();
		$this->annotationTypeReader = new AnnotationTypeReader();
	}

	public function createTypeMap($sourceType, $destinationType, MappingOptionsInterface $mappingOptions)
	{
		$sourceReflector = $this->findReflector($sourceType);
		$destReflector = $this->findReflector($destinationType);

		$typeMap = new TypeMap($sourceType, $destinationType, new SimpleObjectCreator($destReflector));

		/** @var $destMembers \ReflectionProperty[]|\ReflectionMethod[] */
		$destMembers = array_merge(
			ReflectionHelper::getPublicMethods($destReflector, 1),
			$destReflector->getProperties(\ReflectionProperty::IS_PUBLIC)
		);

		foreach ($destMembers as $destMember) {
			if ($destMember instanceof \ReflectionMethod && $destMember->isConstructor()) {
				continue;
			}

			$sourceMembers = array();

			$setter = $this->memberAccessFactory->createMemberSetter($destMember, $mappingOptions);
			$getter = $this->mapDestinationMemberToSource($sourceMembers, $sourceReflector, $destMember->getName(), $mappingOptions)
				? $this->memberAccessFactory->createMemberGetter($sourceMembers, $mappingOptions)
				: null;

			$typeMap->addPropertyMap(new PropertyMap($setter, $getter));
		}

		return $typeMap;
	}

	/**
	 * @param \ReflectionProperty[]|\ReflectionMethod[] $sourceMembers
	 * @param \ReflectionClass $sourceReflector
	 * @param string $nameToSearch
	 * @param MappingOptionsInterface $mappingOptions
	 * @return bool
	 */
	public function mapDestinationMemberToSource(array &$sourceMembers, \ReflectionClass $sourceReflector, $nameToSearch,
		MappingOptionsInterface $mappingOptions)
	{
		$sourceProperties = $sourceReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
		$sourceNoArgMethods = ReflectionHelper::getPublicMethods($sourceReflector, 0);

		$member = $this->findTypeMember($sourceProperties, $sourceNoArgMethods, $nameToSearch, $mappingOptions);

		$foundMatch = $member !== null;

		if ($foundMatch) {
			$sourceMembers[] = $member;
		} else {
			$matches = $this->splitDestinationMemberName($nameToSearch, $mappingOptions);
			$matchesCount = count($matches);

			for ($i = 0; ($i < $matchesCount) && !$foundMatch; $i++) {
				$snippet = $this->createNameSnippet($matches, $i, $mappingOptions);

				$member = $this->findTypeMember($sourceProperties, $sourceNoArgMethods, $snippet['first'], $mappingOptions);
				if ($member !== null) {
					$sourceMembers[] = $member;

					$nestedSourceReflector = $this->parseTypeFromAnnotation($member);

					if ($nestedSourceReflector) {
						$foundMatch = $this->mapDestinationMemberToSource(
							$sourceMembers, $nestedSourceReflector, $snippet['second'], $mappingOptions
						);
					}

					if (!$foundMatch) {
						array_pop($sourceMembers);
					}
				}
			}
		}
		return $foundMatch;
	}

	private function findTypeMember(array $properties, array $getMethods, $nameToSearch, MappingOptionsInterface $mappingOptions)
	{
		/** @var $member \ReflectionProperty|\ReflectionMethod */
		foreach (array_merge($getMethods, $properties) as $member) {
			if ($this->nameMatches($member->getName(), $nameToSearch, $mappingOptions)) {
				return $member;
			}
		}
		return null;
	}

	private function nameMatches($sourceMemberName, $destMemberName, MappingOptionsInterface $mappingOptions)
	{
		$possibleSourceNames = NamingHelper::possibleNames($sourceMemberName, $mappingOptions->getSourcePrefixes());
		$possibleDestNames = NamingHelper::possibleNames($destMemberName, $mappingOptions->getDestinationPrefixes());

		return count(array_uintersect($possibleSourceNames, $possibleDestNames, function($a, $b){
			return strcasecmp($a, $b);
		})) > 0;
	}

	private function splitDestinationMemberName($nameToSearch, MappingOptionsInterface $mappingOptions)
	{
		preg_match_all($mappingOptions->getDestinationMemberNamingConvention()->getSplittingExpression(), $nameToSearch, $matches);
		return isset($matches[0]) ? $matches[0] : array();
	}

	private function createNameSnippet(array $matches, $i, MappingOptionsInterface $mappingOptions)
	{
		return array(
			'first' => implode(
				$mappingOptions->getSourceMemberNamingConvention()->getSeparatorCharacter(),
				array_slice($matches, 0, $i)
			),
			'second' => implode(
				$mappingOptions->getSourceMemberNamingConvention()->getSeparatorCharacter(),
				array_slice($matches, $i)
			),
		);
	}

	/**
	 * @param \ReflectionProperty|\ReflectionMethod $reflector
	 * @return null|\ReflectionClass
	 */
	private function parseTypeFromAnnotation($reflector)
	{
		$type = $this->annotationTypeReader->getType($reflector);
		return $type ? new \ReflectionClass($type) : null;
	}

	private function findReflector($type)
	{
		if (!class_exists($type)) {
			throw new ClassNotFoundException(sprintf('Type <%s> must be class', $type));
		}

		return isset($this->reflectorsCache[$type])
			? $this->reflectorsCache[$type]
			: $this->reflectorsCache[$type] = new \ReflectionClass($type);
	}
}
