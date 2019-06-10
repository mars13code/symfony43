## SYMFONY 4.3


## DEMARRER UN PROJET AVEC SYMFONY

https://symfony.com/doc/current/setup.html

* vérifier la version PHP > 7.1
* installer composer
* https://getcomposer.org/doc/00-intro.md

* installer symfony43 avec la ligne de commande
* on installe la version website-skeleton qui est plus complète
*   (avec la plupart des bundles utiles...)


php composer.phar create-project symfony/website-skeleton symfony43

## INITIALISER GIT

* avec le terminal, dans le dossier symfony43/
* lancer git init

* modifier le fichier .gitgnore


    ## NE PAS GERER CES FICHIERS DANS git
    *.log
    src/Migrations/

* lancer les commandes

    git status
    git add -A
    git commit -a -m "symfony43"

## AJOUTER LE BUNDLE APACHE PACK

* on va utiliser symfony avec un serveur web apache
* => il faut ajouter le fichier public/.htaccess pour les rewrite rules

https://symfony.com/doc/current/setup/web_server_configuration.html

* avec le terminal, dans le dossier symfony43/
* lancer composer pour installer apache-pack

php composer.phar require symfony/apache-pack

* => répondre 'yes'
*       (confirmation demandée acr ce n'est pas une recette officielle)

* => vérifier que le fichier public/.htaccess est bien créé

## VERIFICATION INSTALL

* dans le navigateur, aller sur l'URL

https://localhost/symfony43/public/

* => la page doit afficher une page symfony

* tester une URL qui n'existe pas

https://localhost/symfony43/public/blabla

* => la page doit afficher une erreur symfony (et pas apache...)

## AJOUTER QUELQUES PAGES

https://symfony.com/doc/current/page_creation.html

* avec le terminal, dans le dossier symfony43/
* lancer la ligne de commande

php bin/console make:controller

* on va créer VisitController
* => vérifier que les fichiers ont bien été créés

    created: src/Controller/VisitController.php
    created: templates/visit/index.html.twig

* vérifier que l'url affiche bien une page

https://localhost/symfony43/public/visit

* on va changer cette url pour créer notre page d'accueil
* dans le fichier src/VisitController.php
* changer l'url de la route en "/" (au lieu de "/visit")


    /**
     * @Route("/", name="visit")
     */
    public function index()


* vérifier que l'url affiche bien une page

https://localhost/symfony43/public/


## CONFIGURER DOCTRINE

* MODIFIER LE FICHIER .env
* POUR AJOUTER LES INFOS DE CONNEXION A LA DATABASE MYSQL
* ON VA UTILISER UNE DATABASE symfony43

https://symfony.com/doc/current/doctrine.html

* modifier la ligne suivante dans le fichier .env
* (au besoin changer le user et password MySQL...)


    # DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    DATABASE_URL=mysql://root:@127.0.0.1:3306/symfony43


* lancer la ligne de commande pour créer la database MySQL

php bin/console doctrine:database:create

* on devrait obtenir ce message

    Created database `symfony43` for connection named default

* => vérifier avec phpmyadmin que la database a bien été créée


## CREER UNE ENTITE Contenu

https://symfony.com/doc/current/doctrine.html#creating-an-entity-class

* on va créer une entité Contenu
*   avec comme propriétés
*       titre           string(160)
*       uri             string(160)
*       code            text
*       imageSrc        string(160)
*       categorie       string(160)
*       dateCreation    datetime


* lancer la ligne de commande

php bin/console make:entity

* répondre aux questions...


    created: src/Entity/Contenu.php
    created: src/Repository/ContenuRepository.php

* lancer la création de la table MySQL


    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

* on devrait obtenir ce message

    -> CREATE TABLE contenu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(160) NOT NULL, uri VARCHAR(160) NOT NULL, code LONGTEXT NOT NULL, image_src VARCHAR(160) NOT NULL, categorie VARCHAR(160) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB

* vérifier avec phpmyadmin que la table SQL est bien créée



## CREER LES PAGES CRUD SUR Contenu

https://symfony.com/blog/new-and-improved-generators-for-makerbundle


* lancer la ligne de commande

    php bin/console make:crud Contenu

* on doit obtenir ces fichiers...

    created: src/Controller/ContenuController.php
    created: src/Form/ContenuType.php
    created: templates/contenu/_delete_form.html.twig
    created: templates/contenu/_form.html.twig
    created: templates/contenu/edit.html.twig
    created: templates/contenu/index.html.twig
    created: templates/contenu/new.html.twig
    created: templates/contenu/show.html.twig

* vérifier que les pages CRUD focntionnent correctement

http://localhost/symfony43/public/contenu/

* on va changer le préfixe d'url pour déplacer ces pages dans la partie admin/

    /**
     * @Route("/admin/contenu")
     */
    class ContenuController extends AbstractController



