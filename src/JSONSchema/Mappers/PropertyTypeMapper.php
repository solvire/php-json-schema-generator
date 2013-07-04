<?php
namespace JSONSchema\Mappers;

/**
 * 
 * @package JSONSchema\Mappers
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
        'string' => array('description' => 'A JSON string')'
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
