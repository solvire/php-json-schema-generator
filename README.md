# PHP JSON Schema Generator 
======================

[![Build Status](https://secure.travis-ci.org/solvire/php-json-schema-generator.png)](http://travis-ci.org/solvire/php-json-schema-generator)

Package: php-json-schema-generator


PHP JSON Schema Generator

## Quickstart

Most simple case

    $output = JSONSchemaGenerator\Generator::fromJson('{"a":{"b":2}');
     
    // $output ==> json string
    // {
    //   "$schema": "http://json-schema.org/draft-04/schema#",
    //   "type": "object",
    //   "properties": {
    //     "a": {
    //       "type": "object",
    //       "properties": {
    //         "b": {
    //           "type": "integer"
    //         }
    //       }
    //     }
    //   }
    // }

Default configuration values 

    [
        'schema_id'                      => null,
        'properties_required_by_default' => true,
        'schema_uri'                     => 'http://json-schema.org/draft-04/schema#',
        'schema_title'                   => null,
        'schema_description'             => null,
        'schema_type'                    => null,
        "items_schema_collect_mode"      => 0,
        'schema_required_field_names'    => []
    ]

Advanced usage 

    $result = Generator::fromJson($this->addressJson1, [
        'schema_id' => 'http://foo.bar/schema'
    ]);
    
    /*
    
      {
        "$schema": "http://json-schema.org/draft-04/schema#",
        "id": "http://foo.bar/schema",
        "type": "object",
        "properties": {
          "a": {
            "type": "object",
            "id": "http://foo.bar/schema/a",
            "properties": {
              "b": {
                "id": "http://foo.bar/schema/a/b",
                "type": "integer"
              }
            }
          }
        }
    
    */
    
    
    // if you want items as strict lists instead of "anyOf" type
    $result = Generator::fromJson($this->addressJson1, [
        'schema_id'                      => 'http://bar.foo/schema2',
        'schema_title'                   => 'coucouc',
        'schema_description'             => 'desc',
        "items_schema_collect_mode"      => Definition::ITEMS_AS_LIST,
    ]);
    
    /*
        {
            "$schema":"http:\/\/json-schema.org\/draft-04\/schema#",
            ...
            "properties": {
                "phoneNumber":{
                    "id":"http:\/\/bar.foo\/schema2\/phoneNumber",
                    "type":"array",
                    "items": [ 
                        {"id":"http:\/\/bar.foo\/schema2\/0",...},
                        {"id":"http:\/\/bar.foo\/schema2\/1",...}}
    */
   

For more advanced usage, see `tests/JSONSchemaGenerator/Tests/GeneratorTest.php`


## About JSON Schema

JSON has become a mainstay in the vast HTTP toolbox. In order to make JSON more stable and to increase the longevity of the data structure there must be some standards put into place.  These standards will help to define the structure in a way that the industry can rely on.  A uniform way to parse the data and interpret the meaning of the data elements can be found in building a schema that represents it. 

Due to the flexible nature of JSON a solid Schema definition has been slow coming.  At this time there is an internet draft being worked on.  
(http://tools.ietf.org/html/draft-zyp-json-schema-04)

The uses/pros/cons and other discussions related to what a schema can and cannot do for you are beyond the scope of this document.  Seek your creativity for possible scenarios. 

## About This Tool

It is a bit tedious to have to manually write out a JSON Schema every time a new REST point or Data object is created.  Because JSON can be used to represent a variety of data objects it can be helpful to have a dynamic way to map from one object to a JSON Schema. A php array is considered an object here for the sake of ease of communication.  

The goal of the tool is to provide an trivial implement for generating a JSON Schema off a wide range of objects. Some objects provide more options for rich schema generation and some do not. JSON itself is very light on metadata so there is a requirement to infer certain meanings based on the structure of the objects.  

### Parser Objects Supported
* JSON string
  * Defined [RFC 4627](http://tools.ietf.org/html/rfc4627)
  * No validation yet

### Parsers To Be Supported
* JSON/Schema Object
  * Loading the Schema + Properties manually
  * Can be built up easier with an API 
* Array 
  * Simple hash
  * API to load an array 
  * Will validate the array structure
* ArrayObject 
  * Similar to array hash
* Doctrine Entity
  * Use the Doctrine 2 infrastructure 
  * Generate the schema off the doctrine metadata
  * Map to DB settings
  * Allow map overrides
* Extensible Objects
  * Load user defined parsers
  * Inherit major functionality  

## Installation 
Simple, assuming you use composer. Add the below lines to your composer.json and run composer update.  


    "require": {
        "solvire/php-json-schema-generator": "dev-master"
    }
    
## Testing
PHPUnit should be included with the composer require-dev configuration. The installation will put an
executable in the vendor/bin directly so it can be run from there. 

Run:

    $ vendor/bin/phpunit

