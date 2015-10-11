<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Image extends Base
{
    public  $type="image";

    public function __construct($varname,$name,$options=array())
    {
        parent::__construct($varname,$name,$options);
        $this->class = 'file-loader-'.strtolower($this->getName());
        $this->id    = 'id-loader-'.strtolower($this->getName());
    }

    public function getHTML()
    {
        $html = '<div class="form-group">';
        $html.= '  <label for="'.$this->id.'">'.$this->getName().'</label>';
        $html.= '  {{ form_widget(form.image) }}';
        $html.= '  <div id="{{ form.'.$this->getVarname().'.vars.id }}-preview">';
        $html.= '    {% if entity.'.$this->getVarname().' %}';
        $html.= '    <img src="/{{ entity.'.$this->getVarname().' }}" style="max-width:128px" />';
        $html.= '    {% endif %}';
        $html.= '  </div>';
        $html.= '  <input type="file" id="'.$this->id.'" data-after="{{ form.'.$this->getVarname().'.vars.id }}" name="'.$this->getVarname().'" class="'.$this->class.'" data-url="{{ path(\''.$this->getOptions()['path'].'_upload\') }}" />';
        $html.= '</div>';

        return $html;
    }

    public function getJS()
    {
        $js = '$(".'.$this->class.'").fileupload({';
        $js.= '    dataType: "json",';
        $js.= '    done: function (e, data) {';
        $js.= '        var filename = data.result.filename;';
        $js.= '        var path = data.result.path;';
        $js.= '        var _div = $("#"+$(this).data("after")+"-preview");';
        $js.= '        _div.html(\'<img src="\'+path+\'" style="width:128px" />\');';
        $js.= '        $("#"+$(this).data("after")).val(path);';
        $js.= '     }';
        $js.= '});';

        return $js;

    }

}