# QuackNet
## Ressources
#### **Symfony 5.1** : <br> 
- Symfony : getting started : https://symfony.com/doc/current/setup.html <br>
<br>
#### **Doctrine** :  
- Doctrine ORM documentation : Symfony's doctrine documentation <br>
https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/index.html <br>
https://symfony.com/doc/current/doctrine.html <br>
https://course.larget.fr/content/#/2021_PHP_DIM/courses/6-Doctrine.md <br>
<br>
#### **Twig** :
 - Twig documentation : https://twig.symfony.com/doc/3.x/
- Symfony's twig usages : https://symfony.com/doc/current/templates.html
<br><br>
#### **Autre** : 
- ressources graphiques : https://unsplash.com/s/photos/duck

<hr>

## INSTALLATION 
``` 
composer create-project symfony/website-skeleton QuackNet
```
###Lancer le serveur 
```
php -S 0.0.0.0:8000 -t public
```
puis accéder à l'address : 
http://127.0.0.1:8000/
### La barre d'outils Web Debug: Debugging Dream
L' une des fonctionnalités étonnantes de Symfony est la barre d'outils 
de débogage Web: une barre qui affiche une énorme quantité d'informations 
e débogage en bas de votre page pendant le développement. Tout cela est
 inclus dans la boîte en utilisant un pack Symfony appelé ``symfony/profiler-pack``.

## Bases de données et Doctrine ORM
#### Doctrine ORM
``` 
 composer require symfony/orm-pack
 composer require --dev symfony/maker-bundle
```
#### Bases de données
dans le .env 
````
# customize this line!
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:8000/db_name?serverVersion=5.7"
````
**Création de la base de donnée**
````
php bin/console doctrine:database:create
````

## Créer une Entité
https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
````
php bin/console make:entity
````

###Créer la home 
````
php bin/console make:controller HomeController
````
### Créer les vues associè a l'entité 
**Cela créé aussi un controller et les vues associés
````
php bin/console make:crud Quack
````
### Migrations: création des tables / schémas de base de données 
#### Création de migration pour enregistré l'entité dans la bdd
````
php bin/console make:migration
````
**pour executer les migrations**
````
php bin/console doctrine:migrations:migrate
````
#### Migrations et ajout de champs supplémentaires
Utiliser `make:entity` nouveau et simplement ajouter le champ:
````

$ php bin/console make:entity

Class name of the entity to create or update
> Product

New property name (press <return> to stop adding fields):
> description

Field type (enter ? to see all types) [string]:
> text

Can this field be null in the database (nullable) (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
>
(press enter again to finish)
````
La nouvelle propriété est mappée, mais elle n'existe pas encore dans la table. Générez une nouvelle migration:
````
php bin/console make:migration
````
**pour executer les migrations**
````
php bin/console doctrine:migrations:migrate
````

## Objets persistants dans la base de données
Il est temps d'enregistrer un objet dans la base de données! Créons un nouveau contrôleur pour expérimenter:
````
php bin/console make:controller QuackController
````

## Sécuriser l’interface d’administration
https://symfony.com/doc/current/the-fast-track/fr/15-security.html#configuring-the-security-authentication

Comme pour Twig, le composant de sécurité est déjà installé par des
 dépendances transitives. Ajoutons-le explicitement au fichier ``composer.json`` du projet :
 ````
composer req security
 ````
### Création d'un super Admin
 ````
php bin/console make:user Admin
 ````
Définir un mode de passe encodé pour l'admin : 
 ````
php bin/console security:encode-password

$argon2id$v=19$m=65536,t=4,p=1$ZlJOR1pmYVdzS1A3TWFUQg$kISi0v3MjRIRTd44DP5wncijhC6JRf3fN+IPYS8LBFA
 ````

#### Définir une entité User


#### Configurer le système d’authentification
 ````
php bin/console make:auth
 ````
Sélectionnez 1 pour générer une classe d’authentification pour le formulaire 
de connexion, nommez la classe d’authentification AppAuthenticator, 
le contrôleur SecurityController et créez une URL /logout (yes).
#### Comment ajouter la fonctionnalité de connexion «Se souvenir de moi»
https://symfony.com/doc/current/security/remember_me.html