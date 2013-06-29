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
    
    
    
    
    
}
