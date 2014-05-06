<?php

namespace Papper;

interface MappingConfigurationInterface
{
	public function getSourceType();
	public function getDestinationType();
	public function configure(MappingFluentSyntaxInterface $map);
}
