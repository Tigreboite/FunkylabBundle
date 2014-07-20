##Author

Cyril Pereira <cyril.pereira@gmail.com>

## Installation

Edit `symfony/composer.json` file to add this bundle package:

```yml
"require": {
    ...
    "tigreboite/funkylab-bundle": "dev-master"
},
```

Run `php composer.phar update tigreboite/funkylab-bundle`

Then, add the bundle into `symfony/app/AppKernel.php`:

```php
<?php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Tigreboite\FunkylabBundle\TigreboiteFunkylabBundle(),
        );

        ...

        return $bundles;
    }
```

Add the FunkylabBundle and FOS user-bundle routing file in your symfony/app/config/routing.yml

```yml
...
#Funkylab
tigreboite_funkylab:
    resource: "@TigreboiteFunkylabBundle/Controller/"
    type:     annotation
    prefix:   /funkylab/

#FOS
fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"
```

###Edit the file /app/config/security.yml

```yml
security:
    encoders:
        Symfony\Component\Security\Core\User\User: plaintext
        Tigreboite\FunkylabBundle\Entity\User: sha512

    providers:
        user_db:
            entity: { class: Tigreboite\FunkylabBundle\Entity\User, property: username }

    firewalls:
        dev:
            pattern:   ^/(_(profiler|wdt)|css|images|js)/
            security:  false
            anonymous: true

        secured_area:
            pattern:    ^/
            form_login:
                login_path: funkylab_login
                provider:   user_db
                check_path: fos_user_security_check
                default_target_path: funkylab_home
            logout:
                path:   fos_user_security_logout
                target: funkylab_home
            anonymous:  true

    access_control:
        # Login, password request
        - { path: ^/.*/login, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # Customer access
        - { path: ^/.*, role:  [ROLE_USER, ROLE_ADMIN]}

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN
```

##Console

```
$ php app/console assets:install web --symlink
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
$ php app/console doctrine:fixtures:load
```
#Start

http://domain/funkylab

login : admin@admin.com
pass  : admin


