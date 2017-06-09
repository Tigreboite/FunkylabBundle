#  Extends yourself

## Add your own field

```yaml
# app/Resources/config/services.yml
services:
    appbundle.myfield:
        class: ApplabBundle\Generator\MyField
        tags:
            - {name: "funkylab.fields"}
```

### Create your own Field 

```php
<?php
// src/AppBundle/Generator/Field/myfield.php
namespace AppBundle\Generator\Field;

use Tigreboite\FunkylabBundle\Generator\Field\FieldBase;

class MyField extends FieldBase
{
    public $type = 'myfield'; // dataType used in the entity

    public function getHTML()
    {
        $html = '<div class="form-group">';
        $html .= '  <label for="'.$this->id.'">'.$this->getName().'</label>';
        $html .= '  {{ form_widget(form.image) }}';
        $html .= '  <div id="{{ form.'.$this->getVarname().'.vars.id }}-preview">';
        $html .= '    {% if entity.'.$this->getVarname().' %}';
        $html .= '    <a href="/{{ entity.'.$this->getVarname().' }}">{{ entity.'.$this->getVarname().' }}</a>';
        $html .= '    {% endif %}';
        $html .= '  </div>';
        $html .= '  <input type="file" id="'.$this->id.'" data-after="{{ form.'.$this->getVarname().'.vars.id }}" name="file" class="'.$this->class.'" data-url="{{ path(\''.$this->getOptions()['path'].'_upload\') }}" />';
        $html .= '</div>';

        return $html;
    }
}

```

### How to use your field

```php
  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=100, nullable=false)
   * @Crud("id", datatype="myfield", visible="true",editable="true",sortable="false")
   */
  private $name;
```


## Add your own formater

Add to you your `app/Resources/config/services.yml` 

```yaml
# app/Resources/config/services.yml
services:
    appbundle.myformat:
        class: AppBundle\Generator\MyFormat
        tags:
            - {name: "funkylab.formats"}
```

### Create your formater

```php
<?php

// src/AppBundle/Generator/Formater/myformater.php
namespace AppBundle\Generator\Field;

use Tigreboite\FunkylabBundle\Generator\FormaterBase;

class MyFormater extends FormaterBase
{
    protected $type = 'MyFormater';
}

```

Create those files in `src/AppBundle/Resources/MyFormater`
- `form.html.twig` use as exemple this file [form.html.twig](../../Generator/Resources/views/Datagrid/form.html.twig)
- `index.html.twig` use as exemple this file [form.html.twig](../../Generator/Resources/views/Datagrid/index.html.twig)

Inside the twig you have 
- `%javascript%` is where the javascript will be injected
- `%editable_fields%` is where all the html fields will be injected
- `%admin_entity_path%` is the path of the controller 



