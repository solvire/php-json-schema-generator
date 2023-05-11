<?php

namespace JSONSchemaGenerator;

use JSONSchemaGenerator\Parsers\BaseParser;

/**
 *
 * JSON Schema Generator
 *
 * Duties:
 * Take object arguments
 * Factory load appropriate parser
 *
 *
 * @package JSONSchema
 * @author  solvire
 *
 */
abstract class Generator
{

    /**
     * @param mixed $object
     * @return string
     */
    public static function from($object, array $config = null)
    {
        $parser = new BaseParser($config);
        return $parser->parse($object)->json();
    }

    /**
     * @param string $jsonString
     * @return string
     */
    public static function fromJson($jsonString, array $config = null)
    {
        $parser = new BaseParser($config);
        return $parser->parse(json_decode($jsonString))->json();
    }

}
