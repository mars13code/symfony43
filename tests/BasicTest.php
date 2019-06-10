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
