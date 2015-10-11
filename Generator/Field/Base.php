<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Base
{
    private $varname;
    private $name;

    public function __construct($varname,$name)
    {
        $this->name = $name;
        $this->varname = $varname;
    }

    public function getVarname()
    {
        return $this->varname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>{{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control\'}}) }}
                </div>';
    }

    public function getJS()
    {
        return "";
    }
}