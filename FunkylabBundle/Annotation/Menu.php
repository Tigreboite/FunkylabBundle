<?php

namespace Tigreboite\FunkylabBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class Menu
{
    private $propertyName;
    private $dataType   = 'string';
    private $icon       = 'string';
    private $route      = 'string';
    private $groupe     = 'string';

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

    public function getIcon()
    {
        return $this->icon;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getGroupe()
    {
        return $this->groupe;
    }
}