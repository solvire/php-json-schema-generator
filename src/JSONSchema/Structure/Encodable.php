<?php
namespace JSONSchema\Structure;


interface Encodable
{
    /**
     * @abstract
     * @return array - all the fields that are necessary for encoding 
     */
    public function asEncodeArray();
    
}
