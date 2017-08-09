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
     *
     * @var array $itemFields
     */
    protected $itemFields = array();


    /**
     * (non-PHPdoc)
     * @see JSONSchema\Parsers.Parser::parse()
     */
    public function parse($subject = null)
    {
        // it could have been loaded elsewhere 
        if (!$subject) {
            $subject = $this->subject;
        }

        if (!$jsonObj = json_decode($subject)) {
            throw new Exceptions\UnprocessableSubjectException(
                "The JSONString subject was not processable - decode failed "
            );
        }

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
        foreach ($jsonObj as $key => $property) {
            $this->appendProperty(
                $key,
                $this->determineProperty($property, $key)
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
    protected function determineProperty($property, $name)
    {

        $baseUrl = $this->configKeyExists('baseUrl') ? $this->getConfigSetting('baseUrl') : null;
        $requiredDefault = $this->configKeyExists('requiredDefault') ? $this->getConfigSetting(
            'requiredDefault'
        ) : false;
        $type = StringMapper::map($property);

        if ($type == StringMapper::ARRAY_TYPE) {
            return $this->determineItem($property, $name);
        }

        $prop = new Property();
        $prop->setType($type)
            ->setName($name)
            ->setKey($name)// due to the limited content ability of the basic json string
            ->setRequired($requiredDefault);

        if ($baseUrl) {
            $prop->setId($baseUrl.'/'.$name);
        }

        // since this is an object get the properties of the sub objects 
        if ($type == StringMapper::ARRAY_TYPE) {
            $prop->addItem(
                $name,
                $this->determineItem($property, $name)
            );
        } elseif ($type == StringMapper::OBJECT_TYPE) {
            foreach ($property as $key => $newProperty) {
                $prop->addProperty(
                    $key,
                    $this->determineProperty($newProperty, $key)
                );
            }
        }

        return $prop;
    }


    /**
     * Similar to determineProperty but with a variation
     * Notice that in items list there can be a collection of items - no keys here
     * the total items represent a full definition
     * we are taking the collection of items
     * we should take the cross section of the items and figure out base items
     *
     * @param array $items
     * @param string $name
     * @return Property
     */
    protected function determineItem($items, $name)
    {
        $baseUrl = $this->configKeyExists('baseUrl') ? $this->getConfigSetting('baseUrl') : null;
        $requiredDefault = $this->configKeyExists('requiredDefault') ? $this->getConfigSetting(
            'requiredDefault'
        ) : false;
        $type = StringMapper::map($items);

        $retItem = new Item();
        $retItem->setType($type)
            ->setName($name)
            ->setKey($name)// due to the limited content ability of the basic json string
            ->setRequired($requiredDefault);

        if ($baseUrl) {
            $retItem->setId($baseUrl.'/'.$name);
        }




        // since we stacked the groups of items into their major elements 
        // add ALL of them to the item listings 
        if ($type == StringMapper::ARRAY_TYPE) {
            // loop through and get a list of the definitions 
            // stack them together to find the greatest common 
            foreach ($items as $key => $val) {
                // a collapse of each type
                $this->stackItemFields($name, $val);
            }

            // now that we have our commons lets add them to the items
            foreach ($this->itemFields[$name] as $key => $newItem) {
                $retItem->addItem(
                    $key,
                    $this->determineItem($newItem, $key),
                    true
                );
            }

        } elseif ($type == StringMapper::OBJECT_TYPE) {
            $retItem->addItem(
                $key,
                $this->determineProperty($items, $key)
            );
        }

        return $retItem;
    }

    /**
     *
     * @param string $name
     * @param mixed  $item
     */
    protected function stackItemFields($name, $item)
    {
        // for non-loopables 
        if (!is_array($item) && !is_object($item)) {
            return;
        }
        foreach ($item as $key => $val) {
            $this->itemFields[$name][$key] = $val;
        }
    }


}
