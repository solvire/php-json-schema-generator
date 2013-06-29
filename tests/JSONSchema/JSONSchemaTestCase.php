<?php

namespace JSONSchema;


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
       $dataFile = realpath(__DIR__ . '/../data/example.address.json');
       if(!file_exists($dataFile))
           throw new \RuntimeException("The file: $dataFile does not exist");

       $this->addressJson1 = file_get_contents($dataFile);
       
    }
}
