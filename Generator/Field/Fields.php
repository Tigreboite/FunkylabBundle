<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Fields {

    private $type;
    private $name;
    private $varname;
    private $field;

    public function __construct($type,$name,$varname)
    {
        $this->type     = UCFirst(strtolower($type));
        $this->name     = $name;
        $this->varname  = $varname;

        if(class_exists('Tigreboite\\FunkylabBundle\\Generator\\'.$this->type))
        {
            $this->field = new $this->type($this->varname,$this->name);
        }else{
            $this->field = new Base($this->varname,$this->name);
        }
        dump($this->field);
    }

    public function getHTML()
    {
        return ($this->field) ? $this->field->getHTML() : "";
    }

    public function getJS()
    {
        return ($this->field) ? $this->field->getJS() : "";
    }


}