<?php
namespace JSONSchema\Parsers;

use JSONSchema\Structure\Schema;
use JSONSchema\Parsers\Exceptions\InvalidConfigItemException;

/**
 * Main parser base class
 * 
 * @author steven
 * @package JSONSchema\Parsers
 * @abstract 
 */
abstract class Parser
{
    
    /**
     * the subject that will be parsed 
     * 
     * @var mixed 
     */
    protected $subject = null;
    
    /**
     * by default we should probably assume 
     * that this is going to deliver as a JSON object
     * 
     * @var boolean $isJSONObject
     */
    protected $isJSONObject = true;
    
    /**
     * place holder for the schema object
     * @var JSONSchema\Structure\Schema $schemaObject 
     */
    protected $schemaObject;
    
    /**
     * just config settings 
     * TODO add a roster of config items 
     * @var array $config
     */
    protected $config = array();
    
    /**
     * 
     * @var array $configSchemaRequired
     */
    protected $configSchemaRequiredFields = array();

    
    /**
     * 
     * @param mixed $subject
     * @param array $config
     */
    public function __construct($subject = null, array $config = null)
    {
        $this->subject = $subject;
        if(isset($config['isJSONObject'])) 
            $this->isJSONObject = $config['isJSONObject'];

        $this->config = $config;
        $this->initSchema();
    }
    
    /**
     * TODO implement this function - make sure we check the params 
     * @param array $config
     * @return $this 
     */
    public function loadConfig($config)
    {
        $this->initSchema();
        return $this;
    }
    
    /**
     * TODO need to add some value to this 
     * @return boolean  
     */
    public function validateSchemaConfigVariables()
    {
        return true;
    }
    
    
    /**
     * @param string $key
     */
    public function getConfigSetting($key)
    {
        if(!isset($this->config[$key]) || !is_string($key))
            throw new InvalidConfigItemException();
            
        return $this->config[$key];
    }
    
    /**
     * abstract function for parsing
     * A few concepts we will define here
     * Lets assume that it's a JSON Object type that it will end up
     * 		Properties rather than Items 
     * 
     * @abstract
     * @param mixed $subject
     */
    public function parse($subject=null){}
    
    /**
     * 
     */
    public function initSchema()
    {
        $this->schema = new Schema();
        $this->loadSchema();
    }
    
    /**
     * @param mixed $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * @return $subject
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * providing an empty array to blow out settings  
     * @param array $requiredFields
     * @return $this
     */
    public function setConfigSchemaRequiredFields(array $requiredFields)
    {
        $this->configSchemaRequiredFields = array_merge($this->configSchemaRequiredFields,$requiredFields);
        return $this;
    }
    
    /**
     * @return array 
     */
    public function getConfigSchemaRequiredFields()
    {
        return $this->configSchemaRequiredFields;
    }
    
    /**
     * @param string $key
     * @param JSONSchema\Structure\Property $property
     * @return $this
     */
    protected function appendProperty($key, Property $property)
    {
        $this->schemaObject->addProperty($key, $property);
        return $this;
    }
    
    /**
     * basically set up the schema object with the properties 
     * provided in the config 
     * most items are defaulted as they are probably domain specific
     * 
     * @throws InvalidConfigItemException
     */
    protected function loadSchema()
    {
        if(!$this->validateSchemaConfigVariables())
            throw new InvalidConfigItemException("The provided config does not fill the Schema properly ");
        
        // namespace is schema_
        // try to set all the variables for the schema from the supplied config 
        if(isset($this->config['schema_dollarSchema']))
            $this->schemaObject->setDollarSchema($this->config['schema_dollarSchema']);
        
        if(isset($this->config['schema_required']))
            $this->schemaObject->setRequired($this->config['schema_required']);
            
        if(isset($this->config['schema_title']))
            $this->schemaObject->setTitle($this->config['schema_title']);
            
        if(isset($this->config['schema_description']))
            $this->schemaObject->setDescription($this->config['schema_description']);
            
        if(isset($this->config['schema_type']))
            $this->schemaObject->setType($this->config['schema_type']);

        return $this;
    }

    /**
     * after a successful parse we want to return the json of the Schema 
     * it should probably be compressed string
     * they can beautify it later or maybe we will need to add a beauty function
     * 
     * @return string $json
     */
    public function json()
    {
        
        $schema = $this->schemaObject->toString();
        
        
    }
}
