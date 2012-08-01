MelodyRedirectMobile
====================
Bundle pour la gestion de vos redirections mobiles.


## Installation & Configuration

   1. Download MelodyRedirectMobile
   2. Activation du Bundle
   3. Configuration des redirections
   4. Gestion du routing mobile

### Etape 1: Download MelodyRedirectMobile

Télécharger le bundle et copier les dossier `vendor/bundles/Melody/RedirectMobileBundle`

### Etape 2: Activation du Bundle

On charge le bundle dans le fichier `app/AppKernel.php`:

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

Ensuite dans le fichier `app/autoLoad.php`:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Melody' => __DIR__.'/../vendor/bundles',
));
```

### Etape 3: Configuration des redirections

Dans le fichier `vendor/bundles/Melody/Resources/config/services.yml`, vous avez 2 paramètres que vous pouvez configurer.

   `mrm.mobile` -> correspond à la **ROUTE** ou à **l'URL** de redirection pour les mobiles
   
   `mrm.computer` -> correspond à la **ROUTE** ou à **l'URL** de redirection pour les ordinateurs
  
Si `mrm.computer` est vide, les ordinateurs auront accés aux pages mobiles, sinon ils seront redirigés vers la **ROUTE** ou **l'URL** saisie lors de leurs connexion aux pages mobiles.

### Etape 4: Gestion du routing mobile

Lorsque le site mobile et le site classique sont hébergés dans la même applications, il va vous falloir dissocier vos routes correspondant au site internet classique et mobile.
Pour dissocier les routes il suffit de rajouter une **option** à vos **routes** mobile.

``` yml
    // exemple avec un fichier routing.yml
    AppMonBundle_home_mobile:
    pattern:  /m
    defaults: { _controller: AppMonBundle:Controller:method }
    options:
        mobile: true
```

Si vous avez rempli les deux paramètres présentés lors de l'étape 3, les mobiles auront accés aux routes possèdant l'option **mobile: true** et les ordinateurs aux autres.


   