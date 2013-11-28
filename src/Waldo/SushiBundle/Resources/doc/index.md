SushiBundle
===========

Ceci est un bundle d'expérimentation des fonctionnalités et bonnes pratiques avancées du framework Symfony2.

Configuration de NetBeans
-------------------------

### Completion pour PhpUnit
Pour povoir bénéficier de l'auto-completion dans NetBeans lorsque l'on fait du test avec la librairie PhpUnit, il faut:
Click droit sur le nom du projet, puis `Properties`, menu `PHP include Path`.
Faire `Add Folder` puis rechercher le chemin de PhoUnit, soit `/usr/share/php/PHPUnit`.



Le bundle est autonome
----------------------
Le bundle est un dossier respectant une architecture. Celui-ci doit être autonome, c.â.d qu'il peut être copier/coller et fonctionner (après un peu configuration).

Le javascript/css du bundle doit être dans le répertoire `MonBundle\Resources\Public\[js|css]`

Pour faciliter l'utilisation, il faut ajouter la ligne :
`"symfony-assets-install": "symlink"`
dans le fichier `Composer.js` dans le bloc `extra`.
soit :
 
```js
    "extra": {
        "symfony-app-dir": "app",
        "symfony-web-dir": "web",
        "symfony-assets-install": "symlink"
        // ...
    }
```

[Pour en savoir plus](http://symfony.com/doc/current/book/installation.html#updating-vendors)


Tests
-----

Lorsque l'on test des objets, nous avons souvent besoin de fausses données.
Pour cela nous alons utiliser ces trois librairies : 

- [doctrine/doctrine-fixtures-bundle](https://github.com/doctrine/DoctrineFixturesBundle)
- [fzaninotto/faker](https://github.com/fzaninotto/Faker)
- [nelmio/alice](https://github.com/nelmio/alice)

Ajoutez les dans votre `Composer.js`.
Le DoctrineFixtureBundle requière d'être ajouté dans le fichier `app/AppKernel.php`.
Ajouter le, mais dans la partie réservé à l'environnement de `test` ou `dev`. Ce bundle n'a pas lieu d'être dans un environnement de production.



