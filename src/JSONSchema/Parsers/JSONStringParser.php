<?php
namespace JSONSchema\Parsers;

use JSONSchema\Mappers\StringMapper;

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
        if(!$jsonObj = json_decode($subject))
            throw new Exceptions\UnprocessableSubjectException("The JSONString subject was not processable - decode failed ");
            
        $this->loadObjectProperties($jsonObj);
        $this->loadSchema();
        return $this;
    }
    
    /**
     * 
     * 
     * @param array $jsonObj
     */
    protected function loadObjectProperties($jsonObj)
    {
        // start walking the object 
        foreach($jsonObj as $name => $property)
        {
            if(is_array($property))
            {
                $this->loadObjectProperties($property);
            } else {
                $this->appendProperty(
                    $name,
                    $this->determineProperty(
                        $property,$name
                    )
                );
            }
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
     * @param string $property
     * @param string $name
     * @return Property
     */
    protected function determineProperty(string $property, $name)
    {
        $baseUrl = $this->getConfigSetting('baseUrl');
        $requiredDefault = $this->getConfigSetting('requiredDefault');
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
