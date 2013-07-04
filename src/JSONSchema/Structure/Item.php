<?php
namespace JSONSchema\Structure;

use JSONSchema\Mappers\PropertyTypeMapper;

/**
 * Represents an Item or Element as defined 
 * JSON Array Item
 *  
 * @link http://tools.ietf.org/html/draft-zyp-json-schema-04#section-3.1
 * @link http://tools.ietf.org/html/rfc4627
 * @author steven
 *
 */
class Item extends Property
{
    
    /**
     * one of the few differences is the way it is returned
     * as an array instead of objects 
     * @return array fields 
     */
    public function loadFields()
    {
        // fields array 
        $fa = array();
        $fa['id'] = $this->id;
        $fa['type'] = $this->type;
        $fa['key'] = $this->key;
        $fa['name'] = $this->name;
        $fa['title'] = $this->title;
        $fa['description'] = $this->description;
        $fa['required'] = $this->required;

        if($fa['type'] == PropertyTypeMapper::INTEGER_TYPE || 
            $fa['type'] == PropertyTypeMapper::NUMBER_TYPE )
        {
            if(!empty($this->min)) $fa['min'] = $this->min;
            if(!empty($this->max)) $fa['max'] = $this->max;
        }
        
        
        $properties = $this->getProperties();
        foreach($properties as $property)
        {
            $fa['properties'][] = $property->loadFields();
        }
        
        // add the items 
        $items = $this->getItems();
        foreach($items as $item)
        {
            $sa['items'][] = $item->loadFields();
        }
        
        return $fa;
    }
    
}
