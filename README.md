MelodyRedirectMobile
====================
Bundle pour la gestion de vos redirections mobiles.


## Installation & Configuration

  1. Download MelodyRedirectMobile
  2. Activation du Bundle
  3. Configuration des redirections
  4. Gestion du routing mobile

### Etape 1: Download MelodyRedirectMobile

Télécharger le bundle et copier les dossier `vendor/Melody/RedirectMobileBundle`

### Etape 2: Activation du Bundle

On charge le bundle dans le kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Melody\RedirectMobileBundle\MelodyRedirectMobileBundle(),
    );
}
```

Ensuite dans le fichier autoload.php:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Melody' => __DIR__.'/../vendor/bundles',
));
```

### Etape 3: Configuration des redirections