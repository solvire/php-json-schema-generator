<?php
namespace JSONSchema\Tests;

use JSONSchema\Parsers\JSONStringParser;
use JSONSchema\Generator;
use JsonSchema\Validator;

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
        $json = file_get_contents($file);
        $schema = Generator::fromJson($json);

        $this->assertTrue(!!$schema);

//        print_r([
//            "schema" => json_encode(json_decode($result), JSON_PRETTY_PRINT),
//            'json'   => $json,
//        ]);

        /*
         * Validate schema regarding the spec
         */
        $dereferencer = \League\JsonReference\Dereferencer::draft4();
        $jsonSchemaSchema = $dereferencer->dereference('http://json-schema.org/draft-04/schema#');
        $validator = new \League\JsonGuard\Validator(json_decode($schema), json_decode(file_get_contents(__DIR__.'/../../json-schema.draft4.json')));
        $this->assertFalse($validator->fails(), 'should validate that the schema is a valid json schema draft 4');

        /*
         *  Validate input regarding generated schema
         */
        $validator = new \League\JsonGuard\Validator(json_decode($json), json_decode($schema));
        $this->assertFalse($validator->fails(), 'should validate that the given schema ' . $schema . ' validate the input : ' . $json);
    }

    
    /**
     * the most basic functionality
     * simple tests to just show it's working 
     */
    public function testCanParseSimple()
    {
        $result = Generator::fromJson($this->addressJson1);
        $decoded = json_decode($result);

        $this->assertTrue(is_object($decoded));
        $this->assertTrue(isset($decoded->{'$schema'}));
        $this->assertTrue(isset($decoded->properties));
        $this->assertTrue(isset($decoded->properties->address));
        $this->assertTrue(isset($decoded->properties->address->type));
        $this->assertEquals($decoded->properties->address->type, 'object');
        $this->assertTrue(isset($decoded->properties->phoneNumber));
        $this->assertEquals($decoded->properties->phoneNumber->type,'array');
        $this->assertTrue(is_array($decoded->properties->phoneNumber->items->anyOf));
        $this->assertCount(1, $decoded->properties->phoneNumber->items->anyOf);

    }
    

    /**
     * the most basic functionality
     */
    public function testCanParseExample2()
    {
        $result = Generator::fromJson($this->addressJson2);

        // most of the same tests as example 1
        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);
//        print_r(json_encode($decoded, JSON_PRETTY_PRINT));
        $this->assertTrue(is_object($decoded));
        $this->assertTrue(is_string($decoded->{'$schema'}));
        $this->assertTrue(isset($decoded->properties));
        $this->assertTrue(isset($decoded->properties->bar));
        $this->assertTrue(isset($decoded->properties->bar->properties->barAddress));
        $this->assertTrue(isset($decoded->properties->bar->properties->city));
        $this->assertTrue(isset($decoded->properties->address));
        $this->assertTrue(isset($decoded->properties->address->type));
        $this->assertEquals($decoded->properties->address->type, 'object');
        $this->assertTrue(isset($decoded->properties->phoneNumber));
        $this->assertEquals($decoded->properties->phoneNumber->type,'array');
        $this->assertTrue(is_array($decoded->properties->phoneNumber->items->anyOf));
        $this->assertCount(3, $decoded->properties->phoneNumber->items->anyOf);
        $this->assertTrue(isset($decoded->properties->test));
        $this->assertEquals($decoded->properties->test->type,'string');
        $this->assertEquals($decoded->properties->phoneNumber->id,'http://jsonschema.net/phoneNumber');
        
    }
    
}