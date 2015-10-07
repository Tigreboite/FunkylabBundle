##Author

Cyril Pereira <cyril.pereira@gmail.com>

## Installation

Edit `symfony/composer.json` file to add this bundle package:

```yml
"require": {
    "tigreboite/funkylab-bundle": "dev-master"
},
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
        new Tigreboite\FunkylabBundle\TigreboiteFunkylabBundle(),
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Liip\ImagineBundle\LiipImagineBundle(),
        new Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
        new JMS\TranslationBundle\JMSTranslationBundle(),
        new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
        new Lexik\Bundle\TranslationBundle\LexikTranslationBundle(),
        new Liuggio\ExcelBundle\LiuggioExcelBundle(),
        new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
    );

    ...

    return $bundles;
}
```

edit config.yml and update imports variable

```
imports:
    - { resource: parameters.yml }
    - { resource: services.yml }
    - { resource: "@TigreboiteFunkylabBundle/Resources/config/security.yml" }
    - { resource: "@TigreboiteFunkylabBundle/Resources/config/config.yml" }
```

###Medias

create a directory images

```
$ mkdir web/images
$ chmod -R 777 web/images
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



