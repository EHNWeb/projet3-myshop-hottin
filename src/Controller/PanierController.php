<?php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(PanierService $cs): Response
    {

        $cartWithData = $cs->getCartWithData();
        $totalPanier = $cs->getTotalPanier();

        return $this->render('myshop/show_panier.html.twig', [
            'items' => $cartWithData,
            'totalPanier' => $totalPanier,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, PanierService $cs): Response
    {

        $cs->add($id);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, PanierService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="cart_decrease")
     */
    public function decrease($id, PanierService $cs): Response
    {

        $cs->decrement($id);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/delete", name="cart_delete")
     */
    public function delete(PanierService $cs): Response
    {
       $cs->empty();

       return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/order", name="app_order")
     */
    public function order(PanierService $cs): Response
    {

        $cartWithData = $cs->getCartWithData();
        $totalPanier = $cs->getTotalPanier();

        return $this->render('myshop/show_order.html.twig', [
            'items' => $cartWithData,
            'totalPanier' => $totalPanier,
        ]);
    }

     /**
     * @Route("/cart/buy/{id}", name="cart_buy")
     */
    public function buy($id, PanierService $cs): Response
    {
       $cs->passOrder($id);

       $cs->empty();

       return $this->redirectToRoute('app_order');
    }
}
