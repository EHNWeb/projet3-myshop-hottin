<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class MembreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('pseudo', 'Pseudo'),
            TextField::new('password', 'Mot de passe'),
            EmailField::new('email', 'E-mail'),
            ChoiceField::new('civilite', 'Civilité')->setChoices([
                'Monsieur' => 'm',
                'Madame' => 'f',
                'Mx' => 'x',
            ]),
            TextField::new('nom', 'Nom'),
            TextField::new('prenom', 'Prenom'),
            CollectionField::new('roles', 'Roles')->setTemplatePath('admin/field/roles.html.twig'),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $Membre = new Membre();
        $Membre->setDateEnregistrement(new \DateTime);

        return $Membre;
    }

}
