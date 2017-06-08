<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Image extends Base
{
    public $type = 'image';

    public function __construct($varname, $name, $options = array())
    {
        parent::__construct($varname, $name, $options);

        $this->class = 'file-loader-'.strtolower($this->getName());
        $this->id = 'id-loader-'.strtolower($this->getName());
    }

    public function getHTML()
    {
        $html = '<div class="form-group">';
        $html .= '  <label for="'.$this->id.'">'.$this->getName().'</label>';
        $html .= '  {{ form_widget(form.image) }}';
        $html .= '  <div id="{{ form.'.$this->getVarname().'.vars.id }}-preview">';
        $html .= '    {% if entity.'.$this->getVarname().' %}';
        $html .= '    <img src="/{{ entity.'.$this->getVarname().' }}" style="max-width:128px" />';
        $html .= '    {% endif %}';
        $html .= '  </div>';
        $html .= '  <input type="file" id="'.$this->id.'" data-after="{{ form.'.$this->getVarname().'.vars.id }}" name="file" class="'.$this->class.'" data-url="{{ path(\''.$this->getOptions()['path'].'_upload\') }}" />';
        $html .= '</div>';


        /*<div class="form-group">
            <label for="id-loader-image">Image</label>
            {{ form_widget(form.image) }}
            <div id="{{ form.image.vars.id }}-preview">
                {% if entity.image %}
                    <img src="/{{ entity.image }}" style="max-width:128px" />
                {% endif %}
            </div>
            <input {% if entity.image is not empty %}style="display:none;"{% endif %} type="file" id="id-loader-image" data-after="{{ form.image.vars.id }}" name="file" class="file-loader-image" data-url="{{ path('admin_page_upload') }}" />
            <span class="image-remove btn btn-danger btn-xs" {% if entity.image is empty %}style="display:none;"{% endif %}> <i class="fa fa-remove"></i></span>
        </div>*/

        return $html;
    }

    public function getJS()
    {
        $js = array();
        $js[] = '$(".'.$this->class.'").fileupload({';
        $js[] = '    dataType: "json",';
        $js[] = '    done: function (e, data) {';
        $js[] = '        var _div = $("#"+$(this).data("after")+"-preview");';
        $js[] = '        _div.html("");';
        $js[] = '        if(data.result.success){';
        $js[] = '           var filename = data.result.filename;';
        $js[] = '           var path = data.result.path;';
        $js[] = '          _div.html(\'<img src="/\'+path+\'" style="width:128px;" />\');';
        $js[] = '          $("#"+$(this).data("after")).val(path);';
        $js[] = '        }';
        $js[] = '     }';
        $js[] = '});';

        /*$(".file-loader-image").fileupload({
                dataType: "json",
                done: function (e, data) {
        var _div = $("#"+$(this).data("after")+"-preview");
        _div.html("");
        if(data.result.success){
            var filename = data.result.filename;
            var path = data.result.path;
            _div.html('<img src="/'+path+'" style="width:128px;" />');
            $("#"+$(this).data("after")).val(path);
            _div.parent().find('input[type=file]').hide();
            _div.parent().find('.image-remove').show();
        }
    }
            });

            $('.image-remove').click(function(e)
            {
                e.preventDefault();
                $(this).hide();
                var _file = $(this).parent().find('input[type=file]');
                $("#"+_file.data("after")+"-preview").html("");
                _file.val("");
                $(this).parent().find('input[type=hidden]').val("");
                $(this).parent().find('input[type=file]').show();
            });*/

        return implode("\n", $js);
    }

    public function getBuilder()
    {
        return "\$builder->add('".$this->getVarname()."','hidden');\n";
    }
}
