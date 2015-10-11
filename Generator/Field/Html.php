<?php

namespace Tigreboite\FunkylabBundle\Generator\Field;

class Html extends Base
{

    public  $type="html";

}

/*
 * $('#{{ form.content.vars.id }}').editable({
              inlineMode: false,

              buttons: ["bold", "italic", "underline", "formatBlock", "sep", "insertUnorderedList", "sep", "createLink", "insertImage", "insertVideo", "sep", "undo", "redo", "html"],
              imagesLoadURL:Routing.generate('admin_media_list')+"?dir=page",
              imageDeleteURL:Routing.generate('admin_media_delete')+"?dir=page",
              blockTags: {
                n: 'Normal',
                h2: 'Heading 2',
                h3: 'Heading 3',
                h4: 'Heading 4'
              },

              defaultImageAlignment: "left",
              defaultVideoAlignment: "left",

              imageUploadURL: Routing.generate('upload_image_wysiwyg'),

              imageUploadParams: {id: "my_editor"}
            });
 */