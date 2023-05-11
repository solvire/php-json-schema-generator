<?php
namespace JSONSchemaGenerator\Parsers\Exceptions;

/**
 * @author solvire
 */
class NoParserFoundException extends \RuntimeException
{
    public function __construct($message = "Parser not found", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
