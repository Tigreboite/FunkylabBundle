# Installation


## Install 

```bash
composer require tigreboite/funkylab-bundle 
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
      new JMS\SerializerBundle\JMSSerializerBundle(),
      new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
      new Tigreboite\FunkylabBundle\TigreboiteFunkylabBundle(),
  );

  ...

  return $bundles;
}
```

edit `app/config/config.yml` and update imports variable

```yaml
imports:
  - { resource: parameters.yml }
  - { resource: services.yml }
  - { resource: "@TigreboiteFunkylabBundle/Resources/config/security.yml" }
  - { resource: "@TigreboiteFunkylabBundle/Resources/config/config.yml" }
```

edit `app/config/routing.yml` and add :

```yaml
tigreboite_routing:
  resource: "@TigreboiteFunkylabBundle/Resources/config/routing.yml"
```

### Medias

create a directory images

```bash
mkdir web/images
chmod -R 777 web/images
```

### Assets

```bash
ln -s vendor/almasaeed2010/adminlte/ web/bundles/adminlte
```

### Console

```bash
php app/console assets:install web --symlink
php app/console d:s:u --force
php app/console doctrine:fixtures:load
```

## Configuration

Add those parameters to you `app/config/parameters.yml.dist` :

```yaml
    froala_editor_key: null
    admin_skin: red
    locale: fr
```

`froala_editor_key` : is the serial you get form https://www.froala.com/wysiwyg-editor 
`admin_skin` : you have choices red, green, blue, yellow, orange and red-light, green-light ..*[]: 
`locale` : your locale

Funkylab have by default a set of entities and controllers to help you to create website : 

- user
- page
- actuality (blog like)

you can easily disable all of them by adding this config to your `app/config/config.yml`

```yaml
tigreboite_funkylab:
    controllers:
        user: false
        page: false
        actuality: false
```


## Start

http://yourwebsite.local/admin/login

login : admin@admin.com
pass  : admin
