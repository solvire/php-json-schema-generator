<?php
namespace JSONSchema;


class GeneratorTest extends JSONSchemaTestCase
{
    
    
    /**
     * the most basic functionality
     */
    public function testCanParseSimple()
    {
        $result = Generator::JSONString($this->addressJson1);
    }
    
}