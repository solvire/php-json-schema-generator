<?php
namespace JSONSchemaGenerator\Parsers;

use JSONSchemaGenerator\Mappers\StringMapper;
use JSONSchemaGenerator\Parsers\Exceptions\NoStructureFoundException;
use JSONSchemaGenerator\Structure\Definition;
use JSONSchemaGenerator\Structure\Schema;
use JSONSchemaGenerator\Parsers\Exceptions\InvalidConfigItemException;

/**
 * Main parser base class
 *
 * @author  steven
 * @package JSONSchemaGenerator\Parsers
 * @abstract
 */
class BaseParser
{


    /**
     * place holder for the schema object
     * @var Schema $schemaObject
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
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->config = array_merge([
            'schema_id'                      => null,
            'properties_required_by_default' => true,
            'schema_uri'                     => 'http://json-schema.org/draft-04/schema#',
            'schema_title'                   => null,
            'schema_description'             => null,
            'schema_type'                    => null,
            "items_schema_collect_mode"      => 0,
            'schema_required_field_names'    => []
        ], $config ? $config : []);

        $this->initSchema();
    }


    /**
     *
     */
    public function initSchema()
    {
        $this->schemaObject = new Schema();
        $this->loadSchema();
    }

    /**
     * @param string     $key
     * @param Definition $property
     * @return $this
     */
    protected function appendProperty($key, Definition $property)
    {
        if (!isset($this->schemaObject)) {
            throw new NoStructureFoundException("The Schema is not attached or is not initialized. ");
        }

        if (in_array($property->getType(), [StringMapper::ARRAY_TYPE, StringMapper::OBJECT_TYPE], true)) {
            $property->setId($this->schemaObject->getId() ? $this->schemaObject->getId().'/'.$key : null);
        }

        $this->schemaObject->setProperty($key, $property);

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
        if (isset($this->config['schema_id'])) {
            $this->schemaObject->setId($this->config['schema_id']);
        }

        // namespace is schema_
        // try to set all the variables for the schema from the supplied config 
        if (isset($this->config['schema_dollarSchema'])) {
            $this->schemaObject->setDollarSchema($this->config['schema_dollarSchema']);
        }

        if (isset($this->config['schema_required'])) {
            $this->schemaObject->setRequired($this->config['schema_required']);
        }

        if (isset($this->config['schema_title'])) {
            $this->schemaObject->setTitle($this->config['schema_title']);
        }

        if (isset($this->config['schema_description'])) {
            $this->schemaObject->setDescription($this->config['schema_description']);
        }

        if (isset($this->config['schema_type'])) {
            $this->schemaObject->setType($this->config['schema_type']);
        }

        return $this;
    }

    /**
     * @param null|string $subject
     * @return $this
     */
    public function parse($subject = null)
    {
        $this->loadObjectProperties($subject);
        $this->loadSchema();

        return $this;
    }

    /**
     * top level every recurse under this will add to the properties of the property
     * @param mixed $inputVar
     * @return self
     */
    protected function loadObjectProperties($inputVar)
    {
        // start walking the object
        $type = StringMapper::map($inputVar);

        $this->schemaObject->setType($type);

        if ($type === StringMapper::STRING_TYPE) {
            $this->schemaObject->setFormat(StringMapper::guessStringFormat($inputVar));
        }

        /*
         * Top level schema is a simple salar value, just stop there
         */
        if (!in_array($type, [StringMapper::ARRAY_TYPE, StringMapper::OBJECT_TYPE], true)) {
            return $this;
        }

        /*
         * Top level Schema is an array of an object, continue for deep inspection
         */
        foreach ($inputVar as $key => $property) {
            $property = $this->determineProperty($property);

            if (in_array($property->getType(), [StringMapper::ARRAY_TYPE, StringMapper::OBJECT_TYPE], true)) {
                $property->setId($this->schemaObject->getId() ? $this->schemaObject->getId().'/'.$key : null);
            }

            $type == StringMapper::ARRAY_TYPE ? $this->schemaObject->addItem($property)
                                              : $this->schemaObject->setProperty($key, $property);
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
     * @param mixed $property
     * @return Definition
     */
    protected function determineProperty($property, $id = null)
    {
        $id = $id ?: $this->config['schema_id'];
        $requiredDefault = $this->config['properties_required_by_default'];

        $type = StringMapper::map($property);

        $prop = new Definition();
        $prop->setType($type)
             ->setCollectionMode($this->config['items_schema_collect_mode']
                               ? Definition::ITEMS_AS_LIST
                               : Definition::ITEMS_AS_COLLECTION)
            ->setRequired($requiredDefault);

        if ($type === StringMapper::STRING_TYPE) {
            $prop->setFormat(StringMapper::guessStringFormat($property));
        }

        /*
            since this is an object get the properties of the sub objects
         */
        if (   $type == StringMapper::ARRAY_TYPE
            || $type == StringMapper::OBJECT_TYPE
        ) {

            $prop->setId($id);

            foreach ($property as $key => $p) {
                $def = $this->determineProperty($p, $prop->getId() ? $prop->getId().'/'.$key : null);
                ($type == StringMapper::OBJECT_TYPE) ? $prop->setProperty($key, $def) : $prop->addItem($def);
                if (in_array($key, $this->config['schema_required_field_names'], true)) {
                    $def->setRequired(true);
                }
            }
        }

        return $prop;
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
        return $this->schemaObject->toString();
    }
}
