<?php
namespace JSONSchema\Parsers;

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
     * abstract function for parsing
     * @abstract
     * @param mixed $subject
     */
    public function parse($subject=null){}
    
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
    
    
}