<?php

namespace Tigreboite\FunkylabBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class Crud
{
    private $propertyName;
    private $dataType   = 'string';
    private $editable   = 'string';
    private $visible    = 'string';
    private $sortable   = 'string';

    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function getVisible()
    {
        return $this->visible=="true" ? true :false;
    }

    public function getEditable()
    {
        return $this->editable=="true" ? true :false;
    }

    public function getSortable()
    {
        return $this->sortable=="true" ? true :false;
    }
}