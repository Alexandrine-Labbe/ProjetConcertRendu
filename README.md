# Projet Concert


Installation
```
composer install
yarn install
```

Serveur PHP
```
symfony server:start
```

Création structure DB :
```
php bin/console doctrine:schema:update --dump-sql
```

Importation des fixtures :
```
php bin/console doctrine:fixtures:load
```

