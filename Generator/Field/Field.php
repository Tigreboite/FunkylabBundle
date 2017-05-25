<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Field
{
    private $type;
    private $name;
    private $varname;
    private $field;

    public function __construct($type, $name, $varname, $options = array())
    {
        $this->type = UCFirst(strtolower($type));
        $classType = 'Tigreboite\\FunkylabBundle\\Generator\\Field\\'.$this->type;
        $this->name = $name;
        $this->varname = $varname;
        if (class_exists($classType)) {
            $this->field = new $classType($this->varname, $this->name, $options);
        } else {
            $this->field = new Base($this->varname, $this->name, $options);
        }
    }

    public function getHTML()
    {
        return ($this->field) ? $this->field->getHTML() : '';
    }

    public function getJS()
    {
        return ($this->field) ? $this->field->getJS() : '';
    }

    public function getBuilder()
    {
        return ($this->field) ? $this->field->getBuilder() : '';
    }
}
