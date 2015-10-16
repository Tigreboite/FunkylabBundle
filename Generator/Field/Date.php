<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Date extends Base
{

    public  $type="date";

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>
                    {{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control date\'}}) }}
                </div>';
    }

    public function getBuilder()
    {
        return "\$builder->add('".$this->getVarname()."', 'date', array('required' => false,'widget'   => 'single_text','input'    => 'datetime','format'   => 'dd/MM/yyyy'));\n";
    }
}