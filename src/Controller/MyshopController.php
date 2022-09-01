<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MemberType;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/profil_show", name="profil_show")
     */
    public function show_membre(): Response
    {
        return $this->render('myshop/show_membre.html.twig'); 
    }

     /**
     * @Route("/profil_edit/{id}", name="profil_edit")
     */
    public function edit_membre($id, Request $superGlobals, EntityManagerInterface $manager, MembreRepository $repo): Response
    {
        $membre = $repo->find($id);
        $form = $this->createForm(MemberType::class, $membre);

        $form->handleRequest($superGlobals);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageForm = "L'utilisateur n° " . $membre->getId() . " a été modifié !";
            $manager->persist($membre);
            $manager->flush();
            $this->addFlash('success', $messageForm);
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('myshop/membre_form.html.twig', [
            'formMembre' => $form->createView()
        ]);

    }

}
