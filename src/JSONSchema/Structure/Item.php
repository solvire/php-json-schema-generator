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
     * Because the Item structure is of Array type we
     * need to pass in the parent ID differently
     * For now we can just hard code an :id field but later
     * it needs to have keys for various reasons
     * 
     * @see JSONSchema\Structure.Property::loadFields()
     */
    public function loadFields($parentId = null)
    {
        $arrParentId = $parentId ? $parentId . '/:id' : null; 
        return parent::loadFields($arrParentId);
    }
}
