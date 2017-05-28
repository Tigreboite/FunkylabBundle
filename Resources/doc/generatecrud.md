# Generate CRUD

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

## Datagrid

Add the repository Tigreboite\FunkylabBundle\Entity\BaseRepository to your entity

```php
  /**
   * Car
   *
   * @ORM\Table(name="car")
   * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\BaseRepository")
   */
  class Car
```

## Fields

You can specify the kind of field you want to display
date,file,html,image,number,string

by default it's string.

## Console

You can generate admin interface like form tree and datagrid.

variables are optionnal

`entity` is the entity you want to generate CRUD for
`bundle` is the bundle where you want generate the code
`type` is simpleform|datagrid|sortable

```bash
php app/console funkylab:crud --entity=AppBundle/Entity/Car --bundle=AppBundle --type=datagrid
```
