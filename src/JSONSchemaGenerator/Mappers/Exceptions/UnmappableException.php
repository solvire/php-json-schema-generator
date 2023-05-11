<?php
namespace JSONSchemaGenerator\Mappers\Exceptions;

/**
 * @package JSONSchemaGenerator\Mappers\Exceptions
 * @author steven
 */
class UnmappableException extends \InvalidArgumentException
{
    public function __construct($message = "The parameter you provided is not mappable. ", $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
