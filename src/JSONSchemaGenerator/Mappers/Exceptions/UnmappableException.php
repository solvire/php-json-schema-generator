<?php
namespace JSONSchemaGenerator\Structure\Exceptions;

/**
 * @package JSONSchemaGenerator\Structure\Exceptions
 * @author steven
 */
class UnmappableException extends \InvalidArgumentException
{
    public function __construct($message = "The parameter you provided is not mappable. ", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
