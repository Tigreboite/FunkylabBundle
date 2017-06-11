<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class FieldBase implements FieldInterface
{
    public $type = 'base';
    private $varname;
    private $name;
    private $options;

    public function config($varname, $name, $options = array())
    {
        $this->name = $name;
        $this->varname = $varname;
        $this->options = $options;
    }

    public function getType()
    {
        return $this->type;
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
        return '';
    }

    public function getUseType()
    {
        return '';
    }

    public function getBuilder()
    {
        return "\$builder->add('".$this->varname."');\n";
    }
}
