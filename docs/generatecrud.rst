#############
Generate CRUD
#############

Configure your entity
=====================

By default, visible and editable is true, but if you want set it
edit you entity and add

  use Tigreboite\FunkylabBundle\Annotation\Crud;

and @Crud annotation to your property

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=100, nullable=false)
   * @Crud("id",visible="true",editable="true",sortable="false")
   */
  private $name;

Datagrid
========

Add the repository Tigreboite\FunkylabBundle\Entity\BaseRepository to your entity

  /**
   * Car
   *
   * @ORM\Table(name="car")
   * @ORM\Entity(repositoryClass="Tigreboite\FunkylabBundle\Entity\BaseRepository")
   */
  class Car

Fields
======

You can specify the kind of field you want to display
date,file,html,image,number,string

by default it's string.

Console
=======

You can generate admin interface like form tree and datagrid.

variables are optionnal

entity = the entity you want to generate CRUD for
bundle = the bundle where you want generate the code
type = simpleform|datagrid|sortable

  $ php app/console funkylab:crud --entity=AppBundle/Entity/Car --bundle=AppBundle --type=datagrid


