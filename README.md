PhlexibleSitemapBundle
======================

The PhlexibleSitemapBundle adds support for a finder field in phlexible.

Installation
------------

1. Download PhlexibleSitemapBundle using composer
2. Enable the Bundle
3. Import PhlexibleSitemapBundle routing
4. Clear the symfony cache

### Step 1: Download PhlexibleSitemapBundle using composer

Add PhlexibleSitemapBundle by running the command:

``` bash
$ php composer.phar require phlexible/sitemap-bundle "~1.0.0"
```

Composer will install the bundle to your project's `vendor/phlexible` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Phlexible\Bundle\SitemapBundle\PhlexibleSitemapBundle(),
    );
}
```

### Step 3: Import PhlexibleSitemapBundle routing

Import the PhlexibleSitemapBundle routing.

For frontend:

``` yaml
# app/config/routing.yml
phlexible_sitemap:
    resource: "@PhlexibleSitemapBundle/Controller/SitemapController.php"
    type: annotation
```

### Step 4: Clear the symfony cache

If you access your phlexible application with environment prod, clear the cache:

``` bash
$ php app/console cache:clear --env=prod
```
