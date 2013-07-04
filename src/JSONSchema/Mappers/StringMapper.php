<?php
namespace JSONSchema\Mappers;

use JSONSchema\Structure\Exceptions\UnmappableException;

/**
 * 
 * @package JSONSchema\Mappers
 * @author steven
 *
 */
class StringMapper extends PropertyTypeMapper
{
    
    /**
     * the goal here would be go into a logic tree and work 
     * from loosest definition to most strict 
     * 
     * @param string $property
     * @throws Exceptions\Unmappable
     */
    public static function map(string $property)
    {
        // need to find a better way to determine what the string is
        switch ($property)
        {
            case (is_float($property)):
                return PropertyTypeMapper::NUMBER_TYPE;
            case (is_int($property)):
                return PropertyTypeMapper::INTEGER_TYPE;
            case (is_bool($property)):
                return PropertyTypeMapper::BOOLEAN_TYPE;
            case (is_array($property)):
                return PropertyTypeMapper::ARRAY_TYPE;
            case (is_null($property)):
                return PropertyTypeMapper::NULL_TYPE;
            case (is_object($property)):
                return PropertyTypeMapper::OBJECT_TYPE;
            case (is_string($property)):
                return PropertyTypeMapper::STRING_TYPE;
            default:
                throw new UnmappableException("The provided argument property");
        }
            
    }
    
}
