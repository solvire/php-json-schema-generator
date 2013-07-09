<?php

namespace JSONSchema\Tests;

use JSONSchema\Parsers\ParserFactory;

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
    

    /**
     * since we will probably use the example.address.json data all over the place lets go ahead and load it up 
     * 
     */
    public function setup()
    {
       $dataFile = realpath(__DIR__ . '/../../data/example.address1.json');
       if(!file_exists($dataFile))
           throw new \RuntimeException("The file: $dataFile does not exist");

       // encoded and decoded to pack it down 
       $this->addressJson1 = json_encode(json_decode(file_get_contents($dataFile)));
       
       
       $dataFile = realpath(__DIR__ . '/../../data/example.address2.json');
       if(!file_exists($dataFile))
           throw new \RuntimeException("The file: $dataFile does not exist");
       $this->addressJson2 = json_encode(json_decode(file_get_contents($dataFile)));

       
       $this->parsers = ParserFactory::getParserTypes();
       
       
    }
}
