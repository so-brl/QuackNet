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

# Les assets 
````
 composer require symfony/asset
````

#Encore
(Voir https://symfony.com/doc/current/frontend/encore/installation.html)

``composer require symfony/webpack-encore-bundle``

``npm install``

Pour les images , dans le ``webpack.config.js`` ajouter : 
```
.copyFiles({
        from: './assets/images',

        // optional target path, relative to the output dir
        //to: 'images/[path][name].[ext]',

        // if versioning is enabled, add the file hash too
        //to: 'images/[path][name].[hash:8].[ext]',

        // only copy files matching this pattern
        pattern: /\.(png|jpg|jpeg|svg|gif|ico)$/
    })

```
Pour compiler les assets une fois:
``npm run dev``

Pour "écouter" le changement d'assets: ``npm run watch``

Penser à relancer encore à chaque fois que le fichier ``webpack.config.js`` a été modifié.


Ajouter des images à un template: 
{# assets/images/logo.png was copied to public/build/logo.png #}
<img src="{{ asset('build/logo.png') }}" alt="ACME logo">

### Installer sass 
``
npm add sass-loader@^8.0.0 node-sass --dev
``
dans le ``webpack.config.js`` décommenter ``.enableSassLoader()``

mofifier ``app.js``
````
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/styles.scss';
// import bsCustomFileInput from 'bs-custom-file-input';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// bsCustomFileInput.init();

console.log('Hello Webpack Encore! Edit me in assets/app.js');


// loads the jquery package from node_modules
// var $ = require('jquery');

// import the function from test.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
//     var test = require('./test');
//     $(document).ready(function() {
//     $('body').prepend('<h1>'+test('Canard boiteux')+'</h1>');
// });
````

si ``Error: Node Sass version 5.0.0 is incompatible with ^4.0.0`` modifier dans package.json ``"sass-loader": "^10.0.5"``

## Conteneur de service
Votre application regorge d'objets utiles: un objet «Mailer» peut vous aider à envoyer des e-mails tandis qu'un autre objet peut vous aider à enregistrer des éléments dans la base de données. Presque tout ce que votre application «fait» est en fait fait par l'un de ces objets. Et chaque fois que vous installez un nouveau bundle, vous avez accès à encore plus!

Dans Symfony, ces objets utiles sont appelés services et chaque service vit dans un objet très spécial appelé le conteneur de services . Le conteneur vous permet de centraliser la façon dont les objets sont construits. Cela vous facilite la vie, favorise une architecture forte et est super rapide!

### Créer uns ervice pour uploader des images 
https://symfony.com/doc/current/controller/upload_file.html