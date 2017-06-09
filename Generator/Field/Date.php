<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Date extends FieldBase
{
    public $type = 'date';

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>
                    {{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control date\'}}) }}
                </div>';
    }

    public function getJS()
    {
        $js = array();

        $js[] = '$("#{{ form.'.$this->getVarname().'.vars.id }}").datepicker({';
        $js[] = '   buttonImageOnly: true,';
        $js[] = '   changeMonth: true,';
        $js[] = '   changeYear: true,';
        $js[] = '   dateFormat: \'dd/mm/yy\',';
        $js[] = '   yearRange: "-1:+2"';
        $js[] = '});';

        return implode("\n", $js);
    }

    public function getBuilder()
    {
        return "\$builder->add('".$this->getVarname()."', DateType::class, array('required' => false,'widget'   => 'single_text','input'    => 'datetime','format'   => 'dd/MM/yyyy'));\n";
    }

    public function getUseType()
    {
        return 'use Symfony\Component\Form\Extension\Core\Type\DateType;';
    }
}
