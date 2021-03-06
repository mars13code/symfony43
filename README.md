## SYMFONY 4.3


## DEMARRER UN PROJET AVEC SYMFONY

https://symfony.com/doc/current/setup.html

* vérifier la version PHP > 7.1
* installer composer
* https://getcomposer.org/doc/00-intro.md

* installer symfony43 avec la ligne de commande
* on installe la version website-skeleton qui est plus complète
*   (avec la plupart des bundles utiles...)


```
php composer.phar create-project symfony/website-skeleton symfony43
```


## INITIALISER GIT

* avec le terminal, dans le dossier symfony43/
* lancer git init

* modifier le fichier .gitignore

```
## NE PAS GERER CES FICHIERS DANS git
*.log
src/Migrations/
```

* lancer les commandes

```
git status
git add -A
git commit -a -m "symfony43"
```

## AJOUTER LE BUNDLE APACHE PACK

* on va utiliser symfony avec un serveur web apache
* => il faut ajouter le fichier public/.htaccess pour les rewrite rules

https://symfony.com/doc/current/setup/web_server_configuration.html

* avec le terminal, dans le dossier symfony43/
* lancer composer pour installer apache-pack


```
php composer.phar require symfony/apache-pack
```

    * => répondre 'yes'
    *       (confirmation demandée car ce n'est pas une recette officielle)
    
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

```
php bin/console make:controller
```

* on va créer VisitController
* => vérifier que les fichiers ont bien été créés

```
    created: src/Controller/VisitController.php
    created: templates/visit/index.html.twig
```

* vérifier que l'url affiche bien une page

https://localhost/symfony43/public/visit

* on va changer cette url pour créer notre page d'accueil
* dans le fichier src/VisitController.php
* changer l'url de la route en "/" (au lieu de "/visit")


```
    /**
     * @Route("/", name="visit")
     */
    public function index()
```


* vérifier que l'url affiche bien une page

    https://localhost/symfony43/public/


## CONFIGURER DOCTRINE

* MODIFIER LE FICHIER .env
* POUR AJOUTER LES INFOS DE CONNEXION A LA DATABASE MYSQL
* ON VA UTILISER UNE DATABASE symfony43

https://symfony.com/doc/current/doctrine.html

* modifier la ligne suivante dans le fichier .env
* (au besoin changer le user et password MySQL...)

```
# DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
DATABASE_URL=mysql://root:@127.0.0.1:3306/symfony43
```


* lancer la ligne de commande pour créer la database MySQL

 ```
   php bin/console doctrine:database:create
```

* on devrait obtenir ce message

```
    Created database `symfony43` for connection named default
```

* => vérifier avec phpmyadmin que la database a bien été créée


## CREER UNE ENTITE Contenu

https://symfony.com/doc/current/doctrine.html#creating-an-entity-class

```

* on va créer une entité Contenu
*   avec comme propriétés
*       titre           string(160)
*       uri             string(160)
*       code            text
*       imageSrc        string(160)
*       categorie       string(160)
*       dateCreation    datetime
```


* lancer la ligne de commande

```
php bin/console make:entity
```

* répondre aux questions...

```
    created: src/Entity/Contenu.php
    created: src/Repository/ContenuRepository.php
```

* lancer la création de la table MySQL

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

* on devrait obtenir ce message

```
    -> CREATE TABLE contenu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(160) NOT NULL, uri VARCHAR(160) NOT NULL, code LONGTEXT NOT NULL, image_src VARCHAR(160) NOT NULL, categorie VARCHAR(160) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
```

* vérifier avec phpmyadmin que la table SQL est bien créée



## CREER LES PAGES CRUD SUR Contenu

https://symfony.com/blog/new-and-improved-generators-for-makerbundle


* lancer la ligne de commande

```
php bin/console make:crud Contenu
```

* on doit obtenir ces fichiers...

```
    created: src/Controller/ContenuController.php
    created: src/Form/ContenuType.php
    created: templates/contenu/_delete_form.html.twig
    created: templates/contenu/_form.html.twig
    created: templates/contenu/edit.html.twig
    created: templates/contenu/index.html.twig
    created: templates/contenu/new.html.twig
    created: templates/contenu/show.html.twig
```

* vérifier que les pages CRUD focntionnent correctement

http://localhost/symfony43/public/contenu/

* on va changer le préfixe d'url pour déplacer ces pages dans la partie admin/

```php
/**
 * @Route("/admin/contenu")
 */
class ContenuController extends AbstractController
```

* les pages CRUD sont maintenant sur cette URL

http://localhost/symfony43/public/admin/contenu/


## CREER UNE ENTITY User

* pour protéger notre partie admin
* on va créer une entity User

https://symfony.com/doc/current/security.html


https://symfony.com/doc/current/security.html#a-create-your-user-class

* lancer la ligne de commande

```
php bin/console make:user
```

* répondre aux questions en laissant les choix par défaut

    created: src/Entity/User.php
    created: src/Repository/UserRepository.php
    updated: src/Entity/User.php
    updated: config/packages/security.yaml

 
* lancer la création de la table MySQL

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate

* si vous avez une version MySQL < 5.7.8
* il y a une erreur car le type JSON n'existe pas 
*               dans les versions plus anciennes de MySQL

* contourner le problème en enlevant la colonne roles
*   (pour le moment, le seul User est admin...)

```


```php
    /**
     * @ORM\Column(type="string", length=160, unique=true)
     */
    private $email;

    /**
     */
    private $roles = [];
```


* effacer les fichiers src/Migrations/Version...
* relancer les commandes de synchronisation

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

* on devrait obtenir un message...

```
    -> CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(160) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
```

* vérifier avec phpmyadmin que tout s'est bien passé...

## CREATION DU CRUD User

* lancer la ligne de commande 

```
php bin/console make:crud User
```

* => on doit avoir les nouveaux fichiers

```
    created: src/Controller/UserController.php
    created: src/Form/UserType.php
    created: templates/user/_delete_form.html.twig
    created: templates/user/_form.html.twig
    created: templates/user/edit.html.twig
    created: templates/user/index.html.twig
    created: templates/user/new.html.twig
    created: templates/user/show.html.twig
```

* modifier le préfixe de route pour ajouter /admin/


```php
    /**
     * @Route("/admin/user")
     */
    class UserController extends AbstractController
```


## hashage du mot de passe

https://www.php.net/manual/fr/function.password-hash.php

```php

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // hashage du mot de passe
            $passwordNonHash = $user->getPassword();
            $passwordHash = password_hash($passwordNonHash, PASSWORD_ARGON2I);
            $user->setPassword($passwordHash);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

```


## AJOUT DU FORMULAIRE DE LOGIN

https://symfony.com/doc/current/security/form_login_setup.html

* lancer la commande 

```
php bin/console make:auth
```

* répondre aux questions en donnant comme réponses:


```
    What style of authentication do you want? [Empty authenticator]:
      [0] Empty authenticator
      [1] Login form authenticator
     > 1
    
     The class name of the authenticator to create (e.g. AppCustomAuthenticator):
     > LoginFormAuthenticator
    
     Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
     > 
    
     created: src/Security/LoginFormAuthenticator.php
     updated: config/packages/security.yaml
     created: src/Controller/SecurityController.php
     created: templates/security/login.html.twig
```


* vérifier que le formulaire de login focntionne correctement


    https://localhost/symfony43/public/login
    
* dans la barre du profiler, on doit voir le User connecté...


## PROTEGER LA PARTIE admin/

* on va donner le role ROLE_ADMIN à User
* et on va demander le role ROLE_ADMIN pour toutes les uri préfixées avec /admin/


* changer la méthode getRoles dans src/Entity/User.php

```php

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }
```


* changer le fichier config/packages/security.yaml

```
    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
```


* vérifier que maintenant, on ne peut se connecter aux pages /admin/
* que si on s'est bien identifié sur la page /login


## REDIRECTION VERS LA PAGE admin/contenu

* changer le code dans src/Security/LoginFormAuthenticator.php
* modifier la méthode onAuthenticationSuccess
* pour rediriger vers la route contenu_index

```php

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('contenu_index'));
    }

```

* => vérifier que sur la page de /login
* =>    si on entre les infos de login pour un bon User
* =>        on est alors redirigé vers la page admin/contenu


## AJOUTER LE LIEN DE logout

https://symfony.com/doc/current/security.html#logging-out

* ajouter dans config/packages/security.yaml

```

            logout:
                path:   app_logout
                # where to redirect after logout
                target: app_login                
                
```


* ajouter la méthode logout dans src/security/SecurityController.php

```php

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

```


* on a maintenant dans le profiler un lien pour se déconnecter
* et si on clique sur le lien
* alors on est déconnecté, et on arrive sur la page de /login

## AJOUT LIENS VERS LES DIFFERENTES PARTIES DU SITE


* pour naviguer entre les parties du site
* on va ajouter un menu

https://symfony.com/doc/current/templating.html

* modifier le fichier templates/base.html.twig 

```

        <header>
            <h1>site symfony43</h1>
            <nav>
                <ul>
                    <li><a href="{{ path('visit') }}">accueil</a></li>
                    <li><a href="{{ path('app_login') }}">login</a></li>
                    <li><a href="{{ path('app_logout') }}">logout</a></li>
                    <li><a href="{{ path('contenu_index') }}">admin contenu</a></li>
                    <li><a href="{{ path('user_index') }}">admin user</a></li>
                </ul>
            </nav>
        </header>

```


## TESTS 


https://symfony.com/doc/current/testing.html

* lancer la ligne de commande

```
php bin/console make:unit-test
```

* on doit obtenir un nouveau fichier

```
    created: tests/BasicTest.php
```

* lancer tous les tests

```
php bin/phpunit
```

* => au premier lancement, cela provoque l'installation des bundles nécessaires...

* on devrait obtenir ce message

```
    
    Testing Project Test Suite
    .                                                                   1 / 1 (100%)
    
    Time: 39 ms, Memory: 4.00MB
    
    OK (1 test, 1 assertion)
    
```

## test avec doctrine

https://symfony.com/doc/current/testing/doctrine.html

* on va tester que la création d'un contenu fonctionne correctement

```php
<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\Entity\Contenu;
use App\Repository\ContenuRepository;
use Doctrine\Common\Persistence\ObjectManager;

class BasicTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
    
    public function testSomething()
    {
        $this->assertTrue(true);
    }

    public function testContenuPersist()
    {
        // https://symfony.com/doc/current/testing/doctrine.html
        
        // nbContenu avant insertion
        $contenuRepository = $this->entityManager->getRepository(Contenu::class);
        $nbContenu = $contenuRepository->count(["categorie" => "blog"]);

        // création nouveau contenu
        $contenu = new Contenu;
        
        $contenu->setTitre("titre ".date("H-i-s Ymd"));
        $contenu->setUri("uri-".date("H-i-s-Ymd"));
        $contenu->setCode("code ".date("H-i-s-Ymd"));
        $contenu->setCategorie("blog");
        $contenu->setImageSrc("assets/images/test.jpg");
        $contenu->setDateCreation(new \DateTime);
        
        // persistence 
        $this->entityManager->persist($contenu);
        $this->entityManager->flush();

        // vérification: nbContenu2 après insertion
        $nbContenu2 = $contenuRepository->count(["categorie" => "blog"]);
        
        // vérification qu'un entité Contenu est bien ajoutée
        $this->assertEquals(1, $nbContenu2 - $nbContenu);
        
    }
}

```


## AMELIORATION DES FORMULAIRES

* On va ajouter un peu plus de sécurité sur les formulaires

https://symfony.com/doc/current/validation.html

* On va ajouter sur User que la propriété email
*   doit être un email
*   doit être unique

https://symfony.com/doc/current/reference/constraints/Email.html

https://symfony.com/doc/current/reference/constraints/UniqueEntity.html


* On va ajouter sur User que la propriété uri
*   doit être unique

* Dans le formulaire CRUD, on enlève le champ dateCreation
* et on ajoute une méthode __construct sur Contenu pour initialiser la date 

## AJOUT DE BOOTSTRAP

* on va améliorer l'affichage des pages en ajoutant bootstrap
* pour la partie admin, c'est rapide de compter sur bootstrap
* pour la partie visiteur, ce sera plus simple de ne pas utiliser bootstrap, mais flex

https://symfony.com/doc/current/form/bootstrap4.html

```
# config/packages/twig.yaml
twig:
    form_themes: ['bootstrap_4_layout.html.twig']
```


https://getbootstrap.com/docs/4.3/getting-started/introduction/

* modifier le template templates/base.html.twig

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{% block title %}Welcome!{% endblock %}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        {% block stylesheets %}{% endblock %}
    </head>
    <body>
        <div class="container">
            <header>
                <h1>site symfony43</h1>
                <nav>
                    <ul>
                        <li><a href="{{ path('visit') }}">accueil</a></li>
                        <li><a href="{{ path('app_login') }}">login</a></li>
                        <li><a href="{{ path('app_logout') }}">logout</a></li>
                        <li><a href="{{ path('contenu_index') }}">admin contenu</a></li>
                        <li><a href="{{ path('user_index') }}">admin user</a></li>
                    </ul>
                </nav>
            </header>
            {% block body %}{% endblock %}
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        {% block javascripts %}{% endblock %}
    </body>
</html>
```


## AJOUT PAGE blog

* on va ajouter une nouvelle page blog
* sur cette page, on ne va afficher que les contenus dans la catégorie 'blog'
* ils seront triés du plus récent au plus ancien
* pour le moment, on affiche tous les contenus
*   (ensuite il faudra ajouter la pagination)

* ajouter une route dans VisitController
* ajouter un template src/visit/blog.html.twig
* ajouter le lien dans le menu

```
https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database

https://twig.symfony.com/doc/2.x/templates.html

https://symfony.com/doc/current/templating.html#linking-to-assets
```

## AJOUT UPLOAD D'IMAGE SUR Contenu

* créer le dossier public/assets/media/
* qui servira à stocker les images uploadés lors de la création de Contenu

https://symfony.com/doc/current/controller/upload_file.html

* ajouter 2 paramètres dans le fichier config/services.yaml
* dossier_public
* dossier_media
* => cela permettra une meilleure ré-utilisation du code du projet
* 

```
# config/services.yaml

# ...
parameters:
    dossier_public: '%kernel.project_dir%/public'
    dossier_media: 'assets/media'
```

* ajouter une propriété imageUpload dans src/Entity/Contenu.php
* ajouter une contrainte pour valider que c'est un fichier image
* dans la méthode constructeur
*   initialiser la propriété imageSrc avec "" pour passer la validation du formulaire

* attention: cette propriété ne sera pas associée à une colonne SQL
* cette propriété imageUpload sert uniquement pour le formulaire

```php

// ...

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

// ...

    /**
     * @Assert\Image()
     * cette propriété sert pour le formulaire d'upload 
     */
    public $imageUpload;

    /**
     *  constructeur 
     */
    public function __construct ()
    {
        // initiliser à la date actuell
        $this->dateCreation = new \DateTime;
        // pour passer la validation du formulaire
        $this->imageSrc     = "";  
        $this->imageUpload  = null;
    }

// ...
    
```

* ajouter le champ dans le formulaire
*   en ajoutant le champ dans src/Form/ContenuType.php
*   et enlever le champ pour imageSrc

```

// ...

        $builder
            ->add('titre')
            ->add('uri')
            ->add('code')
            ->add('categorie')
            ->add('imageUpload')
// ...
    
```

* ajouter le code de traitement du formulaire dans la méthode du controleur
* src/Controller/ContenuController.php
* 

    
```
// ...

    /**
     * @Route("/new", name="contenu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contenu = new Contenu();
        $form = $this->createForm(ContenuType::class, $contenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitement de l'upload
            $imageUpload = $contenu->imageUpload;
            if ($imageUpload != null)
            {
                $dossierPublic = $this->getParameter("dossier_public");
                $dossierMedia = $this->getParameter("dossier_media");
                $dossierCible = "$dossierPublic/$dossierMedia";
                
                // TODO: normaliser le nom du fichier pour enlever les caractères spéciaux
                $originalName = $imageUpload->getClientOriginalName();
                // on déplace le fichier hors de la zone de quarantaine
                $imageUpload->move($dossierCible, $originalName);
                
                // stocker le chemin vers l'image
                $contenu->setImageSrc("$dossierMedia/$originalName");
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contenu);
            $entityManager->flush();

            return $this->redirectToRoute('contenu_index');
        }

        return $this->render('contenu/new.html.twig', [
            'contenu' => $contenu,
            'form' => $form->createView(),
        ]);
    }

// ...

```

* tester le formulaire en uploadant une image
* attention: bug avec bootstrap4, le champ input ne montre pas qu'un fichier a été choisi :-/

* vérifier que le fichier est bien créé dans le dossier public/assets/media/
* vérifier que le chemin est bien dans la propriété imageSrc
* vérifier que sur la page blog/ les articles affichent bien l'image

* ajouter dans .gitignore le dossier public/assets/media/

```
public/assets/media/
```



