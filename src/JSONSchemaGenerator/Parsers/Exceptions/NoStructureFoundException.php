<?php
namespace JSONSchemaGenerator\Parsers\Exceptions;

/**
 * @author solvire
 */
class NoStructureFoundException extends \RuntimeException
{
    public function __construct($message = "Parser not found", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
