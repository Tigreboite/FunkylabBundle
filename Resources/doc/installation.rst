#############
Installation
#############

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

Medias
======

create a directory images

```
$ mkdir web/images
$ chmod -R 777 web/images
```

AdminLTE JS/CSS
===============

```
$ln -s vendor/almasaeed2010/adminlte/ web/bundles/adminlte
```

Console
=======

$ php app/console assets:install web --symlink
$ php app/console d:s:u --force
$ php app/console doctrine:fixtures:load
```

Start
=====

http://yourwebsite.local/admin/login

login : admin@admin.com
pass  : admin