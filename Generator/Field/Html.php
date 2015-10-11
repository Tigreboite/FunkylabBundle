<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Html extends Base
{

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>{{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control\'}}) }}
                </div>';
    }

    public function getJS()
    {

    }

}