SushiBundle
===========

Ceci est un bundle d'expérimentation des fonctionnalités et bonnes pratiques avancées du framework Symfony2.


Le bundle est autonome
----------------------
Le bundle est un dossier respectant une architecture. Celui-ci doit être autonome, c.â.d qu'il peut être copier/coller et fonctionner (après configuration).

Le javascript/css du bundle doit être dans le répertoire ```MonBundle\Resources\Public\[js|css]```

Pour faciliter l'utilisation, il faut ajouter la ligne :
```"symfony-assets-install": "symlink"```
dans le fichier ```Composer.js``` dans le bloc ```extra```.
soit :
 
``"extra": {
"symfony-app-dir": "app",
"symfony-web-dir": "web",
"symfony-assets-install": "symlink"
}``

Pour en savoir plus `<http://symfony.com/doc/current/book/installation.html#updating-vendors>`_ 



