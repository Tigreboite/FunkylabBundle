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
      new Shapecode\Bundle\HiddenEntityTypeBundle\ShapecodeHiddenEntityTypeBundle(),
      new Liip\ImagineBundle\LiipImagineBundle(),
      new Vich\UploaderBundle\VichUploaderBundle(),
      new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
      new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
      new Knp\Bundle\MenuBundle\KnpMenuBundle(),
      new FOS\UserBundle\FOSUserBundle(),
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

you need to define a locale, put it in your `app/config/parameters.yml.dist` :

```yaml
    locale: fr
```
 
`locale` : your locale

Funkylab have by default a set of entities and controllers to help you to create website : 

- user
- page
- actuality (blog like with comment and category)

you can configure funkylab `app/config/config.yml`

```yaml
tigreboite_funkylab:
    name: Funkylab CMS
    shortname: TOTO
    skin: red
    froala_editor_key: null
    default_menu:
        user: false
        page: true
        actuality: true
```

`froala_editor_key` : is the serial number you get form https://www.froala.com/wysiwyg-editor 
`skin` : you have choices between red, green, blue, yellow, orange and red-light, green-light ...:
`default_menu` : you can disable any default menu if you don't need them

## Start

http://yourwebsite.local/admin/login

login : admin@admin.com
pass  : admin
