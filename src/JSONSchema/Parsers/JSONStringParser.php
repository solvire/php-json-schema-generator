<?php
namespace JSONSchema\Parsers;

use JSONSchema\Structure\Item;

use JSONSchema\Parsers\Exceptions\InvalidParameterException;
use JSONSchema\Mappers\StringMapper;
use JSONSchema\Mappers\PropertyTypeMapper;
use JSONSchema\Structure\Property;

/**
 * 
 * 
 * @author steven
 *
 */
class JSONStringParser extends Parser
{
    
    /**
     * (non-PHPdoc)
     * @see JSONSchema\Parsers.Parser::parse()
     */
    public function parse($subject = null)
    {
        // it could have been loaded elsewhere 
        if(!$subject) $subject = $this->subject;
        
        if(!$jsonObj = json_decode($subject))
            throw new Exceptions\UnprocessableSubjectException("The JSONString subject was not processable - decode failed ");
            
        $this->loadObjectProperties($jsonObj);
        $this->loadSchema();
        return $this;
    }
    
    /**
     * top level 
     * every recurse under this will add to the properties of the property 
     * 
     * @param array $jsonObj
     */
    protected function loadObjectProperties($jsonObj)
    {
        // start walking the object 
        foreach($jsonObj as $key => $property)
        {
            $this->appendProperty(
                $key, 
                $this->determineProperty($property,$key)
            );
        }
    }

    
    /**
     * due to the fact that determining property will be so different between 
     * parser types we should probably just define this function here
     * In a JSON string it will be very simple.
     *   enter a string
     *   see what the string looks like
     *     check the maps of types 
     *     see if it fits some semantics  
     * 
     * @param object $property
     * @return Property
     */
    protected function determineProperty($property,$name)
    {
        
        $baseUrl = $this->configKeyExists('baseUrl') ? $this->getConfigSetting('baseUrl') : null ;
        $requiredDefault = $this->configKeyExists('requiredDefault') ? $this->getConfigSetting('requiredDefault') : false;
        $type = StringMapper::map($property);
        
        
        if($type == StringMapper::ARRAY_TYPE)
            $prop = new Item();
        else 
            $prop = new Property();
        
        $prop->setType($type)
            ->setName($name)
            ->setKey($name)    // due to the limited content ability of the basic json string
            ->setRequired($requiredDefault);
            
        if($baseUrl) 
            $prop->setId($baseUrl . '/' . $name);
        
        // since this is an object get the properties of the sub objects 
        if($type == StringMapper::OBJECT_TYPE || $type == StringMapper::ARRAY_TYPE)
            foreach($property as $key => $newProperty)
                $prop->addProperty($key, 
                    $this->determineProperty($newProperty, $key));
            
        return $prop;
    }
    
    
    /**
     * Similar to determineProperty
     * If there is a list of items add them individually  
     * 
     * @param string $item
     * @param string $name
     * @return Property
     */
    protected function determineItem($item, $name)
    {
        if(!is_string($item))
            throw new InvalidParameterException();
            
        $baseUrl = $this->configKeyExists('baseUrl') ? $this->getConfigSetting('baseUrl') : null ;
        $requiredDefault = $this->configKeyExists('requiredDefault') ? $this->getConfigSetting('requiredDefault') : false;
        $type = StringMapper::map($property);
        
        
        $prop = new Property();
        $prop->setType($type)
            ->setName($name)
            ->setKey($name)    // due to the limited content ability of the basic json string
            ->setRequired($requiredDefault);
            
        if($baseUrl) 
            $prop->setId($baseUrl . '/' . $name);
        
        return $prop;
    }
    
    
}
