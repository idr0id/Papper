<?php

namespace Papper\Examples\MappingToStdClass;

use Papper\MemberOption\MapFrom;
use Papper\Papper;

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
	public $name = 'John';
	public $family = 'Smith';
	public $age = 32;
}

Papper::createMap('Papper\Examples\MappingToStdClass\User', 'stdClass')
	->forDynamicMember('fullname', new MapFrom(function(User $s) {
		return $s->name . ' ' . $s->family;
	}))
	->forDynamicMember('age', new MapFrom('age'))
;

/** @var \stdClass $userDTO */
$userDTO = Papper::map(new User())->toType('stdClass');

print_r($userDTO);

/*
 * The above example will output:
 *
 * stdClass Object
 * (
 *     [fullname] => John Smith
 *     [age] => 32
 * )
 *
 */
