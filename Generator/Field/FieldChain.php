<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class FieldChain
{
    private $fields;

    public function __construct()
    {
        $this->fields = array();
    }

    /**
     * Add a field to the service
     * @param FieldInterface $field
     */
    public function add(FieldInterface $field)
    {
        $this->fields[$field->getType()] = $field;
    }

    /**
     * Get all fields
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * get one field
     * @param $fieldName
     * @return mixed
     */
    public function getField($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;
    }

}
