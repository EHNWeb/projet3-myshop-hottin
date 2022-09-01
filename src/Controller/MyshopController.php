<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyshopController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/myshop", name="app_myshop")
     */
    public function index(ProduitRepository $repo, EntityManagerInterface $manager): Response
    {

        $produits = $repo->findAll();

        return $this->render('myshop/index.html.twig', [
            'tabProduits' => $produits
        ]);
    }
}
