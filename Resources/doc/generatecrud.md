# Generate CRUD

## Command

You can generate admin interface like form tree and datagrid.

variables are optionnal

`entity` is the entity you want to generate CRUD for
`bundle` is the bundle where you want generate the code
`type` is simpleform|datagrid|sortable

```bash
php app/console funkylab:crud --entity=AppBundle/Entity/Car --bundle=AppBundle --type=datagrid
```

it will generate in AppBundle some files

```
"src/AppBundle/Resources/views/Test"
"src/AppBundle/Resources/views/Test"
Files generated in : AppBundle
Controller/TestController.php
Form/Type/TestType.php
Resources/views/Test/form.html.twig
Resources/views/Test/index.html.twig
Done in 00 min 01 sec
```

You can edit all those files as you want to customize them.

## Configure your entity

By default, visible and editable is true, but if you want set it
edit you entity and add

```php
  use Tigreboite\FunkylabBundle\Annotation\Crud;
```

and @Crud annotation to your property

```php
  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=100, nullable=false)
   * @Crud("id",visible="true",editable="true",sortable="false")
   */
  private $name;
```

### Fields

## Datagrid

Add the repository Tigreboite\FunkylabBundle\Entity\BaseRepository to your entity

```php
// AppBundle\Entity\Car.php
use Tigreboite\FunkylabBundle\Traits\Position;

/**
 * @ORM\Table(name="car")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\BaseRepository")
 */
class Car
{
```

and then in your bash type :

```bash
php bin/console funkylab:crud --entity=AppBundle\\Entity\\Car --bundle=AppBundle --type=datagrid
```


## Sortable

You have to use the trait `Position`

```php
// AppBundle\Entity\Car.php
use Tigreboite\FunkylabBundle\Traits\Position;

/**
 * @ORM\Table(name="car")
 * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Repository\BaseRepository")
 */
class Car
{
    use Position;
    
    ...
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->position = 999;
    }
    
    ...
     
``` 

and then in your bash type :

```bash
php bin/console funkylab:crud --entity=AppBundle\\Entity\\Car --bundle=AppBundle --type=sortable
```

## Simpleform

and then in your bash type :

```bash
php bin/console funkylab:crud --entity=AppBundle\\Entity\\Car --bundle=AppBundle --type=simpleform
```

## Fields

You can specify the kind of field you want to display
date,file,html,image,number,string

by default it's string.

