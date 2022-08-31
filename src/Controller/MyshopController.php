<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyshopController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/myshop", name="app_myshop")
     */
    public function index(): Response
    {
        return $this->render('myshop/index.html.twig', [
            'controller_name' => 'MyshopController',
        ]);
    }
}
