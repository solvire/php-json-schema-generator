<?php
namespace JSONSchema\Structure;

use JSONSchema\Mappers\PropertyTypeMapper;
use JSONSchema\Mappers\StringMapper;

/**
 * Represents a Definition or Member as defined
 *
 * @link   http://tools.ietf.org/html/draft-zyp-json-schema-04#section-3.1
 * @link   http://tools.ietf.org/html/rfc4627
 * @author steven
 *
 */
class Definition implements \JsonSerializable
{


    const ITEMS_AS_COLLECTION = 0; // use anyOf instead of strict collection
    const ITEMS_AS_LIST = 1;

    /**
     * link to the resource identifier
     *
     * @var string $id
     */
    protected $id;

    /**
     * @var string $type
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     * needs to be allowed to be set as a default config setting
     *
     * @var boolean
     */
    protected $required = false;

    /**
     * When numeric it's integer min or max
     * When it's array it's min/max items
     *
     * @var integer
     */
    protected $min = null;

    /**
     * When numeric it's integer min or max
     * When it's array it's min/max items
     *
     * @var integer
     */
    protected $max = null;

    /**
     * sub properties
     *
     * @var Definition[]
     */
    protected $properties = array();


    /**
     * sub items
     *
     * @var Definition[]
     */
    protected $items = array();


    /**
     * If defaultValue instanceof Undefined remove the field from the schema
     * @var mixed default value
     */
    protected $defaultValue;


    /**
     * @var null|array
     */
    protected $enum = null;

    /**
     * @var int items collection mode, convert a list of various schema using anyOf or strict positionnal list of schema
     */
    protected $collectionMode = 0;

    /**
     * setup default values
     */
    function __construct()
    {
        $this->defaultValue = new UndefinedValue();
    }


    /**
     * @return string the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string the $type from JSONSchema\Mappers\StringMapper::*
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return null|string the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null|string the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return boolean the $required
     */
    public function isRequired()
    {
        return !!$this->required;
    }

    /**
     * @return int the $min
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return int the $max
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return Definition[] the $properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return Definition[] the $items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param boolean $required
     * @return self
     */
    public function setRequired($required = false)
    {
        $this->required = !!$required;

        return $this;
    }

    /**
     * @param integer $min
     * @return self
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param integer $max
     * @return self
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param string   $key
     * @param Definition $value
     * @return self
     */
    public function setProperty($key, Definition $value)
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * @param Definition[] $properties
     */
    public function setItems($properties)
    {
        $this->items = [];
        foreach ($properties as $p) {
            $this->addItem($p);
        }
    }

    /**
     * @param Definition $def
     * @return $this
     */
    public function addItem(Definition $def)
    {
        if ($this->getCollectionMode() === self::ITEMS_AS_COLLECTION) {
            $def->setId(null); // make schema anonymous
        }

        foreach ($this->items as $i) {
            if ($this->getCollectionMode() === self::ITEMS_AS_COLLECTION && $i->equals($def)) {
                // item schema type already in list
                return $this;
            }
        }

        $this->items[] = $def;
        return $this;
    }



    /**
     * @return string[] a list of required properties
     */
    public function getRequireds()
    {
        $requireds = [];
        foreach ($this->properties as $name => $p) {
            if ($p->isRequired()) {
                $requireds[] = $name;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return Definition
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     * @return Definition
     */
    public function setEnum($enum)
    {
        $this->enum = $enum;

        return $this;
    }

    /**
     * @return array flattened fields
     */
    public function flatten()
    {
        // field object - to force the object type in json encode 
        $fa = new \stdClass();

        if (!empty($this->getId())) {
            $fa->id = $this->getId();
        }

        $fa->type = $this->getType();

        if ($this->getTitle()) {
            $fa->title = $this->getTitle();
        }

        if ($this->getDescription()) {
            $fa->description = $this->getDescription();
        }

        if ($fa->type == PropertyTypeMapper::INTEGER_TYPE ||
            $fa->type == PropertyTypeMapper::NUMBER_TYPE
        ) {
            if (!empty($this->min)) {
                $fa->min = $this->getMin();
            }
            if (!empty($this->max)) {
                $fa->max = $this->getMax();
            }
        }

        /*
         * If a default value had been set
         */
        if (!$this->defaultValue instanceof UndefinedValue) {
            $fa->default = $this->defaultValue;
        }

        if ($this->getType() === StringMapper::ARRAY_TYPE) {

            // add the items
            $items = [];
            foreach ($this->getItems() as $key => $item) {
                $items[] = $item->flatten();
            }

            if ($this->getCollectionMode() == self::ITEMS_AS_LIST) {
                $fa->items = $items;
            } else {
                // collection of various schema using 'anyOf'
                $fa->items = new \StdClass();
                $fa->items->anyOf = $items;
            }

        } else if ($this->getType() === StringMapper::OBJECT_TYPE) {



            if ($this->getRequireds()) {
                $fa->required = $this->getRequireds();
            }

            if ($this->getProperties()) {
                $fa->properties = new \StdClass();
                foreach ($this->getProperties() as $key => $property) {
                    $fa->properties->$key = $property->flatten();
                }
            }
        }

        return $fa;
    }

    /**
     * @return int
     */
    public function getCollectionMode()
    {
        return $this->collectionMode;
    }

    /**
     * @param int $collectionMode
     * @return Definition
     */
    public function setCollectionMode($collectionMode)
    {
        $this->collectionMode = $collectionMode;

        return $this;
    }



    /**
     * @return \stdClass
     */
    function jsonSerialize()
    {
        return $this->flatten();
    }


    /**
     * @param Definition $d
     * @return bool
     */
    function equals(Definition $d)
    {
        return "$d" === "$this";
    }

    /**
     * @return string
     */
    function __toString()
    {
        return json_encode($this);
    }


}
