SushiBundle
===========

[Lire la documentation](src/Waldo/SushiBundle/Resources/doc/index.md)

Instalation
-----------

Un petit git clone :
```bash
git clone git@github.com:waldo2188/WaldoSushiBundle.git we-love-sushi
```

Une petite mise à jour des vendors : 
```bash
composer update
```

Paramétrer l'accès à la base de données dans le fichier app/config/parameters.yml :
```yaml
parameters:
    database_driver: pdo_sqlite
    database_host: localhost
    database_port: null
    database_name: sushi
    database_user: root
    database_password: null
    database_path: %kernel.root_dir%/cache/sushiDatabase.db
```

Penser bien à décommenter la ligner %database_path% du fichier app/config/config.yml si vous utilisez une base SqLite.

Créer la base de données : 
```bash
php app/console doctrine:schema:create
```

et la mettre en lecture/ecriture

```bash
chmod 777 app/cache/sushiDatabase.db
```

Et, c'est déjà fini !

