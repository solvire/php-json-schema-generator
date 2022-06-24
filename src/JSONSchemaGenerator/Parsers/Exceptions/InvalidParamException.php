<?php
namespace JSONSchemaGenerator\Parsers\Exceptions;

/**
 * @package JSONSchemaGenerator\Parsers\Exceptions
 * @author steven
 */
class InvalidParamException extends \RuntimeException
{
    public function __construct($message = "The provided parameter is not of valid type.", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
