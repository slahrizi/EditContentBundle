# Translatable install :

## Activate the bundle:

``` php
<?php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        );
    }
}
```

## Add extensions to your mapping
``` php
<?php
// app/config/config.yml
doctrine:
    orm:
        mappings:
            translatable:
                type: annotation
                is_bundle: false
                prefix: Gedmo\Translatable\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                alias: GedmoTranslatable
```

## Enable translatable
``` php
<?php
// app/config/config.yml
stof_doctrine_extensions:
    orm:
        default:
            translatable: true
```
