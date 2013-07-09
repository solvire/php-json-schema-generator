<?php

namespace JSONSchema;

use JSONSchema\Parsers\Parser;

/**
 * 
 * JSON Schema Generator
 * 
 * Duties:
 * Take object arguments
 * Factory load appropriate parser 
 * 
 * 
 * @package JSONSchema
 * @author solvire
 *
 */
class Generator
{
    /**
     * @var Parser $parser
     */
    protected $parser = null;
    
    /**
     * 
     * @param mixed $subject
     * @param array $config
     * @return $this instance 
     */
    public function __construct($subject = null, array $config = null)
    {
        if($subject !== null)
            $this->parser = Parsers\ParserFactory::load($subject);
    }
    
    /**
     * @param Parser $parser
     * @return $this
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
        return $this;
    }
    
    /**
     * @return Parser $parser
     */
    public function getParser()
    {
        return $this->parser;
    }
    
    /**
     * @return string
     */
    public function parse()
    {
        return $this->parser->parse();
    }
    
    /**
     * 
     * 
     * @param string $name
     * @param array $arguments
     * 	[0] == payload subject
     *  [1] == config params // not implemented yet 
     */
    public static function __callStatic($name,array $arguments)
    {
        if(!isset($arguments[0]) || !is_string($arguments[0]))
            throw new \InvalidArgumentException("Key: subject must be included in the first position of the array arguments. Provided: " . serialize($arguments));
            
        $parser = Parsers\ParserFactory::loadByPrefix($name,$arguments[0]);
        return $parser->parse()->json();
    }
    
}
