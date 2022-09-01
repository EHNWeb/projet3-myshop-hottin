<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('titre', 'Titre'),
            TextEditorField::new('description', 'Description')->onlyOnForms(),
            TextareaField::new('description', 'Description')
                ->renderAsHtml()
                ->hideOnForm(),
            ChoiceField::new('couleur', 'Couleur')->escapeHtml(false)->setChoices([
                "<span style='background: white;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Blanc</span>" => 'blanc',
                "<span style='background: blue;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Bleu</span>" => 'bleu',
                "<span style='background: red;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Rouge</span>" => 'rouge',
                "<span style='background: yellow;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Jaune</span>" => 'jaune',
                "<span style='background: green;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Vert</span>" => 'green',
                "<span style='background: grey;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Gris</span>" => 'gris',
                "<span style='background: orange;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Orange</span>" => 'orange',
                "<span style='background: pink;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Rose</span>" => 'rose',
                "<span style='background: black;'>&nbsp;&nbsp;&nbsp;</span>&nbsp;Noir</span>" => 'noir',
            ]),
            ChoiceField::new('taille', 'Taille')->setChoices([
                'XS' => 'xs',
                'S' => 's',
                'M' => 'm',
                'L' => 'l',
                'XL' => 'xl',
                '2XL' => '2xl',
            ]),
            ChoiceField::new('collection', 'Genre')->setChoices([
                'Homme' => 'm',
                'Femme' => 'f',
                'Unisexe' => 'u',
            ]),
            ImageField::new('photo')
            ->setBasePath('images/produits/')
            ->setUploadDir('public/images/produits')
            ->setRequired(false)
            ->setUploadedFileNamePattern('[ulid].[extension]'),
            NumberField::new('prix', 'Prix (€)')->setNumDecimals(2),
            IntegerField::new('stock', 'Stock'),
            DateTimeField::new('DateEnregistrement', "Date<br>Enregistrement")->setFormat("dd/MM/Y HH:mm:ss"),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        // La fonction sera exécuter lors de la creation d'un article avant ADD Article
        $produit = new Produit();
        $produit->setDateEnregistrement(new \DateTime);
        $produit->setUpdatedAt(new \DateTime);
        $ifile = $produit->getImageFile();
        if(!$ifile)
        {
            $produit->setPhoto('default.png');
        }
        return $produit;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // La fonction sera exécuter lors de la creation d'un article avant ADD Article
        $ifile = $entityInstance->getPhoto();

        if(!$ifile)
        {
            $entityInstance->setPhoto('default.png');
        }
        $entityInstance->setUpdatedAt(new \DateTime);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }    
}
