<?php
namespace JSONSchemaGenerator\Mappers;

use JSONSchemaGenerator\Parsers\Exceptions\UnmappableException;

/**
 * 
 * @package JSONSchemaGenerator\Mappers
 * @author steven
 *
 */
class PropertyTypeMapper 
{
    
    // a little redundent but a nice key for hitting the arrays 
    const ARRAY_TYPE = 'array';
    const BOOLEAN_TYPE = 'boolean';
    const INTEGER_TYPE = 'integer';
    const NUMBER_TYPE = 'number';
    const NULL_TYPE = 'null';
    const OBJECT_TYPE = 'object';
    const STRING_TYPE = 'string';
    
    /**
     * 
     * 
     * @var string
     */
    protected $property = null;
    
    /**
     * defines the primitive types 
     * @link http://tools.ietf.org/html/draft-zyp-json-schema-04#section-3.5
     * @var array
     */
    protected $primitiveTypes = array(
        'array' => array('description' => 'A JSON array'),
        'boolean' => array('description' => 'A JSON boolean'),
        'integer' => array('description' => 'A JSON number without a fraction or exponent part'),
        'number' => array('description' => 'A JSON number.  Number includes integer.'),
        'null' => array('description' => 'A JSON null value'),
        'object' => array('description' => 'A JSON object'),
        'string' => array('description' => 'A JSON string')
    );
    
    /**
     * 
     * @param string $property
     */
    public function __construct($property)
    {
        if(!is_string($property))
            new \InvalidArgumentException("Parameter provided must be a string");
            
        $this->property = $property;
    }


    /**
     * the goal here would be go into a logic tree and work
     * from loosest definition to most strict
     *
     * @param mixed $property
     * @return string
     * @throws UnmappableException
     */
    public static function map($property)
    {
        // need to find a better way to determine what the string is
        switch (strtolower(gettype($property))) {
            case "double":
            case "float":
                return PropertyTypeMapper::NUMBER_TYPE;
            case 'integer':
                return PropertyTypeMapper::INTEGER_TYPE;
            case 'boolean':
                return PropertyTypeMapper::BOOLEAN_TYPE;
            case 'array':
                if (array_values($property) !== $property) { // hash values
                    return PropertyTypeMapper::OBJECT_TYPE;
                } else {
                    return PropertyTypeMapper::ARRAY_TYPE;
                }
            case 'NULL':
                return PropertyTypeMapper::NULL_TYPE;
            case 'object':
                return PropertyTypeMapper::OBJECT_TYPE;
            case 'string':
                return PropertyTypeMapper::STRING_TYPE;
            default:
                throw new UnmappableException("The provided argument property");
        }
    }
    
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }
    
    public function getProperty()
    {
        return $this->property();
    }
    
    public function getPropertyType()
    {
        
    }
    
}
