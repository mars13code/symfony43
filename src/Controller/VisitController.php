<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ContenuRepository;

class VisitController extends AbstractController
{
    /**
     * @Route("/", name="visit")
     */
    public function index()
    {
        return $this->render('visit/index.html.twig', [
            'controller_name' => 'VisitController',
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(ContenuRepository $contenuRepository)
    {
        // https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
        $tabContenu = $contenuRepository->findBy([ "categorie" => "blog"], ["dateCreation" => "DESC"]);
        
        // on transmet le tableau à twig qui fera une boucle
        // pour afficher chaque contenu dans une balise <article>
        return $this->render('visit/blog.html.twig', [
            "tabContenu"    => $tabContenu
        ]);
    }
}
