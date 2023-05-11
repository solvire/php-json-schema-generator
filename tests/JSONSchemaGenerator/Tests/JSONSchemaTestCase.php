<?php

namespace JSONSchemaGenerator\Tests;

use JSONSchemaGenerator\Parsers\ParserFactory;

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


    public function getDataPath()
    {
        return realpath(__DIR__.'/../../data/');
    }

    /**
     * since we will probably use the example.address.json data all over the place lets go ahead and load it up
     *
     */
    public function setup()
    {
        $this->addressJson1 = json_encode(
            json_decode(file_get_contents($this->getDataPath().'/example-address1.input.json')),
            JSON_PRETTY_PRINT
        );
        $this->addressJson2 = json_encode(
            json_decode(file_get_contents($this->getDataPath().'/example-address2.input.json')),
            JSON_PRETTY_PRINT
        );
    }


    /**
     * @param string $schema
     * @param string $inputJson
     */
    protected function validateSchemaAgainst($schema, $inputJson)
    {
        $this->debug("Schema => $schema", "JSON Struct => $inputJson");

        /*
         * Validate schema regarding the spec
         */
        $dereferencer = \League\JsonReference\Dereferencer::draft4();
        $jsonSchemaSchema = $dereferencer->dereference('http://json-schema.org/draft-04/schema#');
        $validator = new \League\JsonGuard\Validator(
            json_decode($schema),
            json_decode(file_get_contents(__DIR__.'/../../json-schema.draft4.json'))
        );
        $this->assertFalse($validator->fails(), 'should validate that the schema is a valid json schema draft 4');

        /*
         *  Validate input regarding generated schema
         */
        $validator = new \League\JsonGuard\Validator(json_decode($inputJson), json_decode($schema));
        $this->assertFalse(
            $validator->fails(),
            'should validate that the given schema '.$schema.' validate the input : '.$inputJson
        );
    }



    /**
     * display output only if getenv('DEBUG') is set
     */
    protected function debug()
    {
        if (getenv('DEBUG')) {
            foreach (func_get_args() as $a) {
                if (is_string($a)) {
                    empty($a) ? var_dump($a) : print_r($a);
                } else if (is_scalar($a)) {
                    var_dump($a);
                } else {
                    print_r($a);
                }
            }
        }
    }
}
