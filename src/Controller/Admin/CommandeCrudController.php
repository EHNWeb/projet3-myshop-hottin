<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('id_membre', 'Membre')->setTemplatePath('admin/field/membres.html.twig')->onlyOnIndex(),
            AssociationField::new('id_produit', 'Produit')->setTemplatePath('admin/field/produits.html.twig')->onlyOnIndex(),
            IntegerField::new('quantite')->onlyOnIndex(),
            NumberField::new('montant')->setNumDecimals(2)->onlyOnIndex(),
            ChoiceField::new('etat', 'État')->setChoices([
                'En traitement' => '0',
                'Envoyé' => '1',
                'Livré' => '2',
            ])->renderExpanded(),
            DateTimeField::new('date_enregistrement')->onlyOnIndex(),
        ];
    }
  
}
