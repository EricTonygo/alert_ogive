# Alert OGIVE
===========

A Symfony project created on January 1, 2017, 10:30 pm.
## Description du projet

## Prérequis d'installation
Pour démarrer le projet il faut s'assurer d'avoir installer les éléments suivant : 
- PHP 5.5.9 ou une version supérieur (recommandé: PHP 7.x)
- [Composer](https://getcomposer.org/download/)
- Un serveur Web (Apache) en utilisant WAMPP ou Xampp

## Installation
1 . **cloner le projet**
2. Installer les dépendances avec composer `composer install` si un problème de est constaté sur les dépendances notamment un problème de version supprimer le fichier `composer.lock` puis relancer la commande `composer install`
3. Ajuster si nécessaire les paramètres de connexion à la base de données dans le fichier `app/config/parameters.yml`
4. Créer la base de données `php app/console doctrine:database:create`
5. Mettre à jour le schéma de la base de données `php app/console doctrine:schema:update --force`
6. Installer les assets : `php app/console assets:install`
7. Pour lancer le serveur intégré utiliser la commande : `php app/console server:run`
