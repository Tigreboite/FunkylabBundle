##Author

Cyril Pereira <cyril.pereira@gmail.com>

## Installation

```
composer require tigreboite/funkylab-bundle dev-master
```

And do a composer update

Then, add the new bundles into `symfony/app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Lexik\Bundle\TranslationBundle\LexikTranslationBundle(),
        new Liuggio\ExcelBundle\LiuggioExcelBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
        new Tigreboite\FunkylabBundle\TigreboiteFunkylabBundle(),
    );

    ...

    return $bundles;
}
```

edit config.yml and update imports variable

```yml
imports:
    - { resource: parameters.yml }
    - { resource: services.yml }
    - { resource: "@TigreboiteFunkylabBundle/Resources/config/security.yml" }
    - { resource: "@TigreboiteFunkylabBundle/Resources/config/config.yml" }
```

edit routing.yml and add :

```yml
tigreboite_routing:
    resource: "@TigreboiteFunkylabBundle/Resources/config/routing.yml"
```

ontrollers :

You can configure all the controllers of the admin to be displayed or not in the menu

```
tigreboite_funkylab:
  default_menu:
      user: false
      translator: false
      page: false
      blog: false
      country: false
      language: false
```

###Medias

create a directory images

```
$ mkdir web/images
$ chmod -R 777 web/images
```
###AdminLTE JS/CSS

```
$ln -s vendor/almasaeed2010/adminlte/ web/bundles/adminlte
```

##Console

```
$ php app/console assets:install web --symlink
$ php app/console d:s:u --force
$ php app/console doctrine:fixtures:load
```

#Start

http://yourwebsite.local/admin/login

login : admin@admin.com
pass  : admin

#Documentation

##Role and menu

To extend the admin, create a controller in your own bundle :

```php
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
```

Configure with annotation your action's controller  

```php
/**
 * Lists all Language entities.
 *
 * @Route("/", name="admin_language")
 * @Method("GET")
 * @Template()
 * @Menu("Languages", dataType="string", icon="fa-flag", groupe="CMS")
 * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
 */
 public function indexAction()
 {
     return array();
 }
```

Menu : name, icon : image to display in admin, groupe, tab where to put this action   
Security : Role ROLE_MODERATOR, ROLE_BRAND, ROLE_USER, ROLE_ADMIN   

don't forget to add the global route to your controller to be included in the admin.

```php
/**
 * Language controller.
 *
 * @Route("/admin/language")
 */
class LanguageController extends Controller
```

By default the template twig need to be in

```
src/AppBundle/Resources/views/yourcontroller/action/index.html.twig
```

or you can set your template name and path in annotation

##Entity

###Annotation

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

###Datagrid

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

###Fields

You can specify the kind of field you want to display
date,file,html,image,number,string

by default it's string.

##Console

You can generate admin interface like form tree and datagrid.

variables are optionnal

entity = the entity you want to generate CRUD for   
bundle = the bundle where you want generate the code   
type = simpleform|datagrid|sortable   

```
$ php app/console funkylab:crud --entity=AppBundle/Entity/Car --bundle=AppBundle --type=datagrid
```


