<?php

namespace JSONSchema;

use JSONSchema\Parsers\JSONStringParser;
use JSONSchema\Parsers\Parser;

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
     * @param string $jsonString
     * @return string
     */
    public static function fromJson($jsonString, array $config = null)
    {
        $parser = new JSONStringParser($config);
        return $parser->parse($jsonString)->json();
    }

}
