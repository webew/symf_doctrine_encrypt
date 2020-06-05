# secureapp

Cet projet est un projet de démarrage pour l'atelier de sécurisation Web avec Symfony5.

Cet atelier est proposé après l'étude des failles de sécurité éditées par OWASP, afin de mettre en oeuvre les préconisations résultantes au sein d'un projet créé grâce au Framework Symfony 5, sur le site ici : https://secuweb.aesisoft.fr/

___

## Prerequis

Il est necessaire d'avoir un serveur Apache2 avec PHP de version minimum 7.2.<br/>
Il faut également une base de données MySQL ou MariaDB et un client du type PhpMyAdmin.

## Installation

1. Cloner l'application sur votre serveur Apache localhost
2. Configurer l'accès au serveur de données dans le fichier .env
3. Installer les composants :

```Bash
    composer install
```

4. Créer la base de données :

```Bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

___

## Paramétrage de PHP.ini

Pour chiffre/déchiffrer les DCP, il faut activer la bibliothèque ***libsodium*** disponible à partir de la version PHP 7.2 :

```INI
    extension=sodium
```

Si vous avez une erreur d'installation des composants, vous devrez peut-être augmenter la taille de la mémoire utilisée par PHP.

```INI
    memory_limit = 512M
```

Pour tester la taille actuelle :

```Bash
    php -r "echo ini_get('memory_limit').PHP_EOL;"
```
