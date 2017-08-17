# PHP JSON Schema Generator 

![Build Status](https://travis-ci.org/evaisse/php-json-schema-generator.svg?branch=master#)
[![codecov](https://codecov.io/gh/evaisse/php-json-schema-generator/branch/master/graph/badge.svg)](https://codecov.io/gh/evaisse/php-json-schema-generator)

Originaly forked from [solvire/php-json-schema-generator](https://github.com/solvire/php-json-schema-generator)

Introduction to json schema below (and tools) :
 
 - http://json-schema.org — reference
 - http://www.jsonschemavalidator.net - validator (not 100% valid) 
 - https://www.openapis.org - use json schema to define REST API docs
 - https://jsonschema.net/#/editor - convenient editor for json schema 
 
To validate your structure against a given schema, you can use :

 - http://json-guard.thephpleague.com


## Quickstart

Install using composer

    composer require evaisse/php-json-schema-generator
    
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
    //       },
    //       "required": ["b"]
    //     }
    //   },
    //   "required": ["a"]
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
 


    
## Testing

just run phpunit through

    composer test
    
debug with 

    DEBUG=true composer test -- --filter="SearchWord" # for filtering *SearchWord* test case with output debugging


## Roadmap

  - Adjust schema comparison using re-ordering of properties to compare two schema against 
  their semantic values instead of just comparing their JSON form. For example `{ a: 1, b: 2 }`, and `{ b: 2, a: 1 }` 
  should result in the same schema.
  
  - provide an option to allow null values in most fields `("type": ["string", "null"]}`