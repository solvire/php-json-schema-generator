<?php

namespace JSONSchema\Tests;


/**
 * Base test case for JSON Schema Tests
 */
abstract class JSONSchemaTestCase extends \PHPUnit_Framework_TestCase
{
    protected $addressJson1;

    /**
     * since we will probably use the example.address.json data all over the place lets go ahead and load it up 
     * 
     */
    public function setup()
    {
       $dataFile = realpath(__DIR__ . '/../../data/example.address.json');
       if(!file_exists($dataFile))
           throw new \RuntimeException("The file: $dataFile does not exist");

       // encoded and decoded to pack it down 
       $this->addressJson1 = json_encode(json_decode(file_get_contents($dataFile)));
       
    }
}
