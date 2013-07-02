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
        $result = Generator::JSONString(array('subject'=>$this->addressJson1));
    }
    
}