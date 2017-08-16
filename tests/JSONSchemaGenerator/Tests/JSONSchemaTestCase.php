<?php

namespace JSONSchemaGenerator\Tests;

use JSONSchemaGenerator\Parsers\ParserFactory;

/**
 * Base test case for JSON Schema Tests
 */
abstract class JSONSchemaTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * simple address object found on json-schema
     * 
     * @var string
     */
    protected $addressJson1;
    
    /**
     * little more complex version of the 1st address problem
     * @var string
     */
    protected $addressJson2;
    
    /**
     * list of the parser types 
     */
    protected $parsers = array();


    public function getDataPath()
    {
        return realpath(__DIR__.'/../../data/');
    }

    /**
     * since we will probably use the example.address.json data all over the place lets go ahead and load it up 
     * 
     */
    public function setup()
    {
       $this->addressJson1 = json_encode(json_decode(file_get_contents($this->getDataPath().'/example.address1.json')), JSON_PRETTY_PRINT);
       $this->addressJson2 = json_encode(json_decode(file_get_contents($this->getDataPath().'/example.address2.json')), JSON_PRETTY_PRINT);
    }
}
