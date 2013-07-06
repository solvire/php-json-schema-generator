<?php
namespace JSONSchema\Structure;

/**
 * A JSON document 
 * Represents the body of the schema
 * Can be decomposed and consolidated as a simple array of key values for easy json_encoding
 * 
 * @link http://tools.ietf.org/html/draft-zyp-json-schema-04#section-3.2
 * @author steven
 *
 */
use JSONSchema\Mappers\PropertyTypeMapper;

class Schema 
{
    
    /**
     * From section 3.2
     * Object members are keywords 
     * 
     * @var array
     */
    protected $keywords = array();
    
    /**
     * Properties
     * 
     * @var array $properties
     */
    protected $properties = array();
    
    /**
     * Special use case
     * JSON Array 
     * 
     * @var array $items
     */
    protected $items = array();
    
    /**
     * @var string
     */
    protected $dollarSchema  = 'http://json-schema.org/draft-04/schema';
    
    /**
     * properties or items in a list which are required 
     * @var array $required
     */
    protected $required = array();
    
    /**
     * @var string $title
     */
    protected $title  = '';
    
    /**
     * @var string $description
     */
    protected $description = '';

    /**
     * the JSON primitive type
     * Default MUST be object type  
     * Section 3.2 
     * 
     * @var string $type
     */
    protected $type = 'object';
    
    /**
     * type of media content 
     * @var string
     */
    protected $mediaType = 'application/schema+json';
    
    /**
     * during the return it can clean up empty fields 
     * @var boolean 
     */
    protected $pruneEmptyFields = true;

    
    public function __construct()
    {
        
    }
    
    /**
     * @param string $key
     * @param string $value
     * @param boolean $overwrite
     * @return $this
     * @throws Exceptions\OverwriteKeyException
     */
    public function addKeyword($key,$value,$overwrite = true)
    {
        if(!empty($this->keywords[$key]) && !$overwrite)
            throw new Exceptions\OverwriteKeyException();
        
        $this->keywords[$key] = $value;
        return $this;
    }
    
    /**
     * @param string $key
     * @param Property $value
     * @param boolean $overwrite
     * @return $this
     * @throws Exceptions\OverwriteKeyException
     */
    public function addProperty($key,Property $value,$overwrite = true)
    {
        if(!empty($this->properties[$key]) && !$overwrite)
            throw new Exceptions\OverwriteKeyException();
        
        $this->properties[$key] = $value;
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @param boolean $overwrite
     * @return $this
     * @throws Exceptions\OverwriteKeyException
     */
    public function addItem($key,$value,$overwrite = true)
    {
        if(!empty($this->items[$key]) && !$overwrite)
            throw new Exceptions\OverwriteKeyException();
        
        $this->items[$key] = $value;
        return $this;
    }
    
    /**
     * @param string $key
     * @return $this
     */
    public function removeKeyword($key)
    {
        unset($this->keywords[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    
    public function removeProperty($key)
    {
        unset($this->properties[$key]);
        return $this;
    }
    
    /**
     * @param string $key
     * @return $this
     */
    
    public function removeItem($key)
    {
        unset($this->items[$key]);
        return $this;
    }
    
    /**
     * @return array 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
    
    
    /**
     * @return the $dollarSchema
     */
    public function getDollarSchema()
    {
        return $this->dollarSchema;
    }

	/**
     * @return the $required
     */
    public function getRequired()
    {
        return $this->required;
    }

	/**
     * @return the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

	/**
     * @return the $mediaType
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

	/**
     * @param string $dollarSchema
     */
    public function setDollarSchema($dollarSchema)
    {
        $this->dollarSchema = $dollarSchema;
        return $this;
    }

	/**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

	/**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

	/**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

	/**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

	/**
     * @param string $mediaType
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
        return $this;
    }
    
    /**
     * A string representation of this Schema 
     * @return string
     */
    public function toString()
    {
        return json_encode($this->loadFields());
    }
    
    /**
     * simply convert to an array 
     * @return array 
     */
    public function toArray()
    {
        return $this->loadFields();
    }
    
    /**
     * Main schema generation utility 
     * 
     * @return array list of fields and values including the properties/items 
     */
    public function loadFields()
    {
        // schema  holder 
        $sa = new \stdClass();
        $sa->schema = $this->dollarSchema;
        $sa->required = $this->required;
        $sa->title = $this->title;
        $sa->description = $this->description;
        $sa->type = $this->type;
        $sa->mediaType = $this->mediaType; // not really part of the schema but i'm tacking it here for safe handling
        
        // add the items 
        $items = $this->getItems();
        foreach($items as $key => $item)
            $sa->items[$key] = $item->loadFields();
        
        // add the propertiestas  
        $properties = $this->getProperties();
        // it's an object so instantiate one 
        if($properties)
            $sa->properties = new \stdClass();

        foreach($properties as $key => $property)
            $sa->properties->$key = $property->loadFields();

            
        if($sa->type == PropertyTypeMapper::ARRAY_TYPE)
            return (array) $sa;
        else 
            return $sa;
        
    }
}