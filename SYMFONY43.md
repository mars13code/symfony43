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

