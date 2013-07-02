<?php
namespace JSONSchema\Parsers;

/**
 * Helps in loading up the proper parser for the data type provided
 *  
 * 
 * @name ParserFactory
 * @author solvire
 * @package JSONSchema\Parsers
 * @subpackage Parsers
 * 
 */
class ParserFactory 
{
    
    /**
     * static class 
     */
    protected function __construct(){}
    
    /**
     * take the subject and load the appropriate parser with it 
     * 
     * @param mixed $subject
     * @return JSONSchema\Parsers\Parser
     */
    public static function load($subject)
    {
        $prefix = self::getSubjectType($subject);
        return self::loadByPrefix($prefix, $subject);
    }
    
    /**
     * takes a parser name prefix and gets the parser
     * ex JSONString return JSONStringParser
     * 
     * @param string $prefix
     * @param mixed $subject
     * @throws \RuntimeException
     * @return JSONSchema\Parsers\Parser
     */
    public static function loadByPrefix($prefix, $subject = null)
    {
        $classname = 'JSONSchema\Parsers\\' . $prefix . 'Parser';
        
        if(class_exists($classname))
            return new $classname($subject);
        else 
            throw new Exceptions\NoParserFoundException("Class not found: $classname ");
    }
    
    /**
     * determins the subject based on the class name or data type
     * class naming convention is for the parsers is for the object type + Parser 
     * Ex: ArrayObject will be parsed by ArrayObjectParser  
     * 
     * @param mixed $subject
     * @return string subject type 
     * @throws \InvalidArgumentException
     * @throws NoParserFoundException
     */
    public static function getSubjectType($subject = null )
    {
        
        if(empty($subject) || is_null($subject))
            throw new \InvalidArgumentException("The subject type could not be determined from your provided argument: [$subject] ", 422);
        
        if(is_array($subject) === true) return 'Array';
        if(is_string($subject) === true) return 'JSONString';
        if($subject instanceof \ArrayObject) return 'ArrayObject';
        if(is_object($subject)) return 'ObjectParser';
        
        throw new NoParserFoundException();
        
    }
    
}