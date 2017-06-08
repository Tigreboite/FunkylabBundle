<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Html extends Base
{
    public $type = 'html';

    public function getHTML()
    {
        return '<div class="form-group">
                    <label for="{{ form.'.$this->getVarname().'.vars.id }}">'.$this->getName().'</label>
                    {{ form_widget(form.'.$this->getVarname().', {\'attr\':{\'class\': \'form-control wysiwyg\'}}) }}
                </div>';
    }

    public function getJS()
    {
        $js = array();

        $js[] = '$("#{{ form.'.$this->getVarname().'.vars.id }}").froalaEditor({';
        $js[] = '        inlineMode: false,';
        $js[] = '        buttons: ["bold", "italic", "underline", "formatBlock", "sep", "insertUnorderedList", "sep", "createLink", "insertImage", "insertVideo", "sep", "undo", "redo", "html"],';
        $js[] = '        imagesLoadURL: Routing.generate(\'admin_media_list\') + "?dir=page",';
        $js[] = '        imageDeleteURL: Routing.generate(\'admin_media_delete\') + "?dir=page",';
        $js[] = '        blockTags: {';
        $js[] = '           n: "Normal",';
        $js[] = '           h2: "Heading 2",';
        $js[] = '           h3: "Heading 3",';
        $js[] = '           h4: "Heading 4"';
        $js[] = '        },';
        $js[] = '        defaultImageAlignment: "left",';
        $js[] = '        defaultVideoAlignment: "left",';
        $js[] = '        imageUploadURL: Routing.generate(\'upload_image_wysiwyg\'),';
        $js[] = '        imageUploadParams: {id: "my_editor"}';
        $js[] = '    });';

        return implode("\n", $js);
    }
}
