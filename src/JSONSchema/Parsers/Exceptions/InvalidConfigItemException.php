<?php
namespace JSONSchema\Parsers\Exceptions;

/**
 * @package JSONSchema\Parsers\Exceptions
 * @author steven
 */
class InvalidConfigItemException extends \RuntimeException
{
    public function __construct($message = "The config item is invalid", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
