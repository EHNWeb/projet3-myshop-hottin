<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Membre;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyShop');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Admin', 'fa fa-gear'),
            MenuItem::section('e-Boutique', 'fa fa-store'),
            MenuItem::linkToCrud('Produits', 'fa fa-gifts', Produit::class),
            MenuItem::linkToCrud('Commandes', 'fa fa-cart-arrow-down', Commande::class),
            MenuItem::section('Utilisateurs', 'fa fa-users-line'),
            MenuItem::linkToCrud('Membres', 'fa fa-users', Membre::class),
        ];
    }
}
