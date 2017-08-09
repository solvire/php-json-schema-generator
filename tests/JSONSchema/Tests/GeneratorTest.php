<?php
namespace JSONSchema\Tests;

use JSONSchema\Parsers\JSONStringParser;
use JSONSchema\Generator;

/**
 * 
 * @group Generator
 * @author solvire
 * @package Tests
 *
 */
class GeneratorTest extends JSONSchemaTestCase
{

    /**
     * @return array
     */
    public function provideJsonSamples()
    {
        $samples = [];
        $root = realpath(__DIR__.'/../../data/');
        foreach (glob($root.'/*.json') as $k => $v) {
            $samples[substr($v, strlen($root)+1)] = [$v];
        }
        return $samples;
    }

    /**
     * @dataProvider provideJsonSamples
     */
    public function testGeneration($file)
    {
        $result = Generator::JSONString(file_get_contents($file));
        $this->assertEquals($file, '');
    }

    
    /**
     * the most basic functionality
     * simple tests to just show it's working 
     */
    public function testCanParseSimple()
    {
        $result = Generator::JSONString($this->addressJson1,'test');
        $decoded = json_decode($result);
        $this->assertTrue(is_object($decoded));
        $this->assertTrue(isset($decoded->schema));
        $this->assertTrue(isset($decoded->properties));
        $this->assertTrue(isset($decoded->properties->address));
        $this->assertTrue(isset($decoded->properties->address->type));
        $this->assertEquals($decoded->properties->address->type, 'object');
        $this->assertTrue(isset($decoded->properties->phoneNumber));
        $this->assertEquals($decoded->properties->phoneNumber->type,'array');
        $this->assertTrue(is_array($decoded->properties->phoneNumber->items));
        $this->assertTrue(count($decoded->properties->phoneNumber->items) == 2);

    }
    
    
    /**
     * we are using magic methods to call the app for short signature calls
     * We need to know that calling JSONString will call the JSONStringParser and then parse
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testFunctionalClassLoad()
    {
        $generator = new Generator();
        
        $this->assertTrue($generator instanceof Generator);
        $this->assertNull($generator->getParser());
        
        $parser = new JSONStringParser();
        $generator->setParser($parser);
        
        // set the parser and then get the same parser back 
        // the type should not have changed 
        $this->assertSame($generator->getParser(),$parser);
        $this->assertTrue($generator->getParser() instanceof JSONStringParser);
        
        
        // test that we throw an exception if the subject is not provided 
        $result = Generator::JSONString();
        
    }

    /**
     * the most basic functionality
     */
    public function testCanParseExample2()
    {
        $generator = new Generator($this->addressJson2);
        $this->assertTrue($generator->getParser() instanceof JSONStringParser);
        // returns itself for chained calls
        $this->assertTrue($generator->parse() instanceof JSONStringParser);
        $result = $generator->getParser()->json();
        
        // most of the same tests as example 1
        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);
        $this->assertTrue(is_object($decoded));
        $this->assertTrue(isset($decoded->schema));
        $this->assertTrue(isset($decoded->properties));
        $this->assertTrue(isset($decoded->properties->bar));
        $this->assertTrue(isset($decoded->properties->bar->properties->barAddress));
        $this->assertTrue(isset($decoded->properties->bar->properties->city));
        $this->assertTrue(isset($decoded->properties->address));
        $this->assertTrue(isset($decoded->properties->address->type));
        $this->assertEquals($decoded->properties->address->type, 'object');
        $this->assertTrue(isset($decoded->properties->phoneNumber));
        $this->assertEquals($decoded->properties->phoneNumber->type,'array');
        $this->assertTrue(is_array($decoded->properties->phoneNumber->items));
        $this->assertTrue(count($decoded->properties->phoneNumber->items) == 4);
        $this->assertTrue(isset($decoded->properties->test));
        $this->assertEquals($decoded->properties->test->type,'string');
        $this->assertEquals($decoded->properties->phoneNumber->id,'http://jsonschema.net/phoneNumber');
        
    }
    
}