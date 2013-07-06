<?php
namespace JSONSchema\Tests;

use JSONSchema\Generator;

class GeneratorTest extends JSONSchemaTestCase
{
    
    
    /**
     * the most basic functionality
     */
    public function ctestCanParseSimple()
    {
        $result = Generator::JSONString(array('subject'=>$this->addressJson1));
        var_dump($result);
    }
    

    /**
     * the most basic functionality
     */
    public function testCanParseExample2()
    {
        $result = Generator::JSONString(array('subject'=>$this->addressJson2));
        var_dump($result);
    }
    
}