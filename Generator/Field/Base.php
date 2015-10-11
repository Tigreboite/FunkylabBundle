<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Base
{
    public  $type="base";
    private $varname;
    private $name;
    private $options;

    public function __construct($varname,$name,$options=array())
    {
        $this->name     = $name;
        $this->varname  = $varname;
        $this->options  = $options;
    }

    public function getVarname()
    {
        return $this->varname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>
                    {{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control\'}}) }}
                </div>';
    }

    public function getJS()
    {
        return "";
    }
}