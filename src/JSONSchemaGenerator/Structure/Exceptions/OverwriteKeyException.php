<?php
namespace JSONSchemaGenerator\Structure\Exceptions;

/**
 * @package JSONSchemaGenerator\Structure\Exceptions
 * @author steven
 */
class OverwriteKeyException extends \RuntimeException
{
    public function __construct($message = "You are attempting to overwrite a key without forcing it to be. ", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
