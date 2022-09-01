<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreCrudController extends AbstractCrudController
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return Membre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('pseudo', 'Pseudo'),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)->onlyOnForms()->onlyWhenCreating(),
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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getId()) {
            $entityInstance->setPassword(
                $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}

