<?php
namespace App\Service;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService {
    private $rs;
    private $repo;

    public function __construct(RequestStack $rs, ProduitRepository $repo, CommandeRepository $repoCde, MembreRepository $repoMembre, EntityManagerInterface $manager) {
        // Hors d'un controller, nous devons faire les injections de dépendances dans un constructeur
        $this->rs = $rs;
        $this->repo = $repo;
        $this->repoCde = $repoCde;
        $this->repoMembre = $repoMembre;
        $this->manager = $manager;
    }

    public function add($id){
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $cart = $session->get('cart', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            // l'index du tableau cart est l'id du produit
            $cart[$id] = 1;
        }
        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('cart', $cart);
    }

    public function remove($id) {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        // Si le produit existe dans le panier, on le supprime du tableau $cart via unset()
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        // On sauvegarde l'état du panier
        $session->set('cart', $cart);
    }
    
    public function decrement($id) {
        // On va créer une SESSION grâce à la classe RequestStack
        $session = $this->rs->getSession();

        // On récupère l'attribut de SESSION cart s'il existe, sinon , on récupère un tableau vide
        $cart = $session->get('cart', []);
        // Le tableau cart contient les quantités commandées des produits

        // Si le produit existe déjà dans le panier, on incrémente la quantité
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1){
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        // Je sauvegarde l'état de monm panier à l'attribut de session 'cart'
        $session->set('cart', $cart);
    }

    public function empty()
    {
        $session = $this->rs->getSession();
        $session->remove('cart');
    }

    public function getCartWithData()
    {
        // On recupère la SESSION
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        // Quantité totale du panier
        $quantityPanier = 0;

        // on crée un nouveau tableau qui contiendra des objets Product et les quantités de chauque OBJET
        $cartWithData = [];

        // $cartWithData est un tableau multidimensionnel:
        // Pour chaque ID qui se trouve dans le panier, nous allons créer un nouveau tableau dans $cartWithData qui contiendra 2 cases:
        // Product, Quantity
        foreach ($cart as $id => $quantity) {
            // On créer une nouvelle case dans le tableau $cartWithData
            $cartWithData[] = [
                'produit' => $this->repo->find($id),
                'quantite' => $quantity
            ];
            $quantityPanier += $quantity;
        }

        $session->set('totalQuantity', $quantityPanier);

        return $cartWithData;
    }

    public function getTotalPanier()
    {
        $session = $this->rs->getSession();
        $cartWithData = $this->getCartWithData();

        // Pour chaque produit dans mon panier, j erécupère le sous total
        $totalPanier = 0;
        foreach ($cartWithData as $item) {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $totalPanier += $totalItem;
        }

        $session->set('totalPanier', $totalPanier);

        return $totalPanier;
    }

    public function passOrder($id)
    {
        $session = $this->rs->getSession();
        $cartWithData = $this->getCartWithData();

        $messageBDD = "La commande a bien été enregistrée.";
        $cdeDateEnregistrement = new \DateTime();
        $cdeEtat = 0;
        $cdeMembreID = $this->repoMembre->find($id);

        // Pour chaque produit dans mon panier, j erécupère le sous total
        foreach ($cartWithData as $item) {

            $cdeProduitID = $item['produit'];
            $cdeProduitQTY = $item['quantite'];

            $commande = new Commande();
            
            $commande->setIdMembre($cdeMembreID)
                     ->setEtat($cdeEtat)
                     ->setDateEnregistrement($cdeDateEnregistrement)
                     ->setQuantite($cdeProduitQTY)
                     ->addIdProduit($cdeProduitID);

            $this->manager->persist($commande);
            $this->manager->flush();
        }

    }
}