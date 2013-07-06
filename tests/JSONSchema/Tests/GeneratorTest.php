<?php
namespace JSONSchema\Tests;

use JSONSchema\Generator;

class GeneratorTest extends JSONSchemaTestCase
{
    
    
    /**
     * the most basic functionality
     */
    public function testCanParseSimple()
    {
//        print_r(json_decode($this->addressJson1));
        $result = Generator::JSONString(array('subject'=>$this->addressJson1));
        
        var_dump($result);
    }
    
}