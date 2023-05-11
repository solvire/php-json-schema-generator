<?php
namespace JSONSchemaGenerator\Tests;

use JSONSchemaGenerator\Parsers\JSONStringParser;
use JSONSchemaGenerator\Generator;
use JSONSchemaGenerator\Structure\Definition;
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
        $root = $this->getDataPath();
        foreach (glob($root.'/example*.input.json') as $k => $v) {
            $samples[substr($v, strlen($root)+1)] = [$v];
        }
        return $samples;
    }


    public function testBasics()
    {
        $input = '{"a":{"b":2}}';
        $res = \JSONSchemaGenerator\Generator::fromJson($input);
        $expected = '{"$schema":"http:\/\/json-schema.org\/draft-04\/schema#","type":"object","required":["a"],"properties":{"a":{"type":"object","required":["b"],"properties":{"b":{"type":"integer"}}}}}';
        $this->assertEquals($expected, $res);
        $this->validateSchemaAgainst($res, $input);
    }

    /**
     * @dataProvider provideJsonSamples
     */
    public function testDefaultGeneration($file)
    {
        $json = file_get_contents($file);
        $expectedJsonSchema = file_get_contents(str_replace('.input.json', '.schema.json', $file));
        $schema = Generator::fromJson($json);


        $this->assertTrue(!!$schema);

        $this->debug($schema);

        $this->assertJsonStringEqualsJsonString($expectedJsonSchema, $schema);

//        $this->assertEquals($json, json_encode(json_decode($schema), JSON_PRETTY_PRINT));
        $this->validateSchemaAgainst($schema, $json);

        /*
         * Check mocks against their schema
         */
        $this->assertEquals(
            json_encode(json_decode(file_get_contents(str_replace('.input.json', '.schema.json', $file)))), // disable pretty prinitng
            $schema,
            'Should be equal to data example in ' . basename(str_replace('.input.json', '.schema.json', $file))
        );
    }


    
    /**
     * the most basic functionality
     * simple tests to just show it's working 
     */
    public function testCanParseSimple()
    {
        $result = Generator::fromJson($this->addressJson1, [
            'schema_id' => 'http://foo.bar/schema'
        ]);

        $this->debug($result);

        $decoded = json_decode($result);

        $this->validateSchemaAgainst($result, $this->addressJson1);

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
        $result = Generator::fromJson($this->addressJson2, [
            'schema_id' => "http://foo.bar"
        ]);

        $this->validateSchemaAgainst($result, $this->addressJson2);

        // most of the same tests as example 1
        $this->assertTrue(is_string($result));

        $this->debug($result);

        $decoded = json_decode($result);

        $this->debug(json_encode($decoded, JSON_PRETTY_PRINT));

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
        $this->assertEquals($decoded->properties->phoneNumber->id,'http://foo.bar/phoneNumber');
        
    }

    /**
     * the most basic functionality
     * simple tests to just show it's working
     */
    public function testDeduplicationOfSchemaInCollection()
    {
        $result = Generator::fromJson($this->addressJson1);

        $this->debug($result);

        $this->validateSchemaAgainst($result, $this->addressJson1);

        $decoded = json_decode($result);

        $this->assertTrue(is_array($decoded->properties->phoneNumber->items->anyOf));
        $this->assertCount(1, $decoded->properties->phoneNumber->items->anyOf);
    }

    /**
     * the most basic functionality
     * simple tests to just show it's working
     */
    public function testCanParseStrictModeList()
    {
        $result = Generator::fromJson($this->addressJson2, [
            'schema_id'                      => 'http://bar.foo/schema2',
            'schema_title'                   => 'coucouc',
            'schema_description'             => 'desc',
            "items_schema_collect_mode"      => Definition::ITEMS_AS_LIST,
        ]);

        $this->debug($result);

        $this->validateSchemaAgainst($result, $this->addressJson2);

        // most of the same tests as example 1
        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);

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
        $this->assertTrue(is_array($decoded->properties->phoneNumber->items));
        $this->assertCount(4, $decoded->properties->phoneNumber->items);
        $this->assertTrue(isset($decoded->properties->test));
        $this->assertEquals($decoded->properties->test->type,'string');
        $this->assertEquals($decoded->properties->phoneNumber->id,'http://bar.foo/schema2/phoneNumber');

    }


    public function testRequiredProperties()
    {
        $result = Generator::fromJson($this->addressJson2);

        $this->validateSchemaAgainst($result, $this->addressJson2);

        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);

        $this->debug($result);

        $this->assertCount(4, $decoded->required, 'should have required properties');
        $this->assertCount(2, $decoded->properties->bar->required, 'sub selements should have required properties');

        $result = Generator::fromJson($this->addressJson2, [
            'properties_required_by_default' => false,
        ]);

        $this->validateSchemaAgainst($result, $this->addressJson2);

        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);
        $this->assertTrue(!isset($decoded->required), 'should not have the required property');
        $this->assertTrue(!isset($decoded->properties->bar->required), 'sub definitions should not have the required property also');

        $result = Generator::fromJson($this->addressJson2, [
            'properties_required_by_default' => false,
            'schema_required_field_names'    => ['barAddress'], // just make this field required
        ]);

        $this->validateSchemaAgainst($result, $this->addressJson2);

        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);

        $this->assertTrue(!isset($decoded->required), 'should only have the required property for one def');
        $this->assertTrue(!isset($decoded->properties->address->required), 'should only have the required property for one def');
        $this->assertCount(1, $decoded->properties->bar->required, 'One prop only should be required');
        $this->assertContains("barAddress", $decoded->properties->bar->required, '"barAddress", Should be required');

        $this->debug($decoded);
    }



    public function testFrom()
    {
        $result = Generator::from(json_decode($this->addressJson2));

        $this->validateSchemaAgainst($result, $this->addressJson2);

        $this->assertTrue(is_string($result));
        $decoded = json_decode($result);

        $this->debug($result);

        $this->assertCount(4, $decoded->required, 'should have required properties');
        $this->assertCount(2, $decoded->properties->bar->required, 'sub selements should have required properties');
    }

}