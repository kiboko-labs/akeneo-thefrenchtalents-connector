TheFrenchTalents.com connector for Akeneo
=========================================

Pour les instructions en français, veuillez suivre ce lien [en français](#instructions-en-francais)

## TL;DR

This bundle will enable product data import from _thefrenchtalents.com_ service into your Akeneo PIM.

## Installing the bundle

Install the bundle via composer:

`composer require kiboko/akeneo-thefrenchtalents-connector`

Then, in your `app/AppKernel.php`, add the bundle to your bundle list:

```php
    $bundles = [
        // ...
        new Kiboko\Bundle\TheFrenchTalentsConnectorBundle\KibokoTheFrenchTalentsConnectorBundle(),
        // ...
    ];
```

# Instructions en Français

## Installation du bundle

Installez le bundle via composer :

`composer require kiboko/akeneo-thefrenchtalents-connector`

Ensuite, ajoutez le bundle à votre liste de bundles dans le fichier `app/AppKernel.php` :

```php
    $bundles = [
        // ...
        new Kiboko\Bundle\TheFrenchTalentsConnectorBundle\KibokoTheFrenchTalentsConnectorBundle(),
        // ...
    ];
```

