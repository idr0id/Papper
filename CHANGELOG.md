CHANGELOG
=========

* 1.1.1 (2014-09-29)

 * Change naming conventions namespace
 * Improve exception messages
 * Fix to avoid warning in array_map when collection mapping throws exception

* 1.1.0 (2014-07-15)

 * Add null substitute for property mappings
 * Fixes problems with anonymous functions
 * Internal: refactoring of tests

* 1.0.0 (2014-06-27)

 * Add ignores all remaining unmapped members that do not exist on the destination.
 * Add mapping to dynamic members and stdClass
 * Add ignoring of resolving scalar type in annotation hints
 * Add ability to custom mapping from source
 * Add type value converter
 * Add ability to configure mapping via configuration classes
 * Add the ability to map from exists destination object
 * Add trait to get FQCN via static method ::className()
 * Changed interface of mapping to fluent syntax
 * Slight improvements and bug fixes
 * Refactoring of examples

* 1.0.0-rc.5 (2014-06-18)

 * Add ignoring of resolving scalar type in annotation hints
 * Add ability to custom mapping from source
 * Improve mapping configuration exceptions

* 1.0.0-rc.4 (2014-05-13)

 * Small improves
 * Bug fixing

* 1.0.0-rc.3 (2014-05-07)

 * Added type value converter
 * Added ability to configure mapping via configuration classes
 * Internal improvements

* 1.0.0-rc.2 (2014-04-10)

 * Changed interface of mapping to fluent syntax
 * Added the ability to map from exists destination object
 * Fixed incorrect computing of hash for array of typemaps
 * Removed redundant TypeMapInterface
 * Added trait to get FQCN via static method ::className()
 * Fixed checking of type for created destination object
