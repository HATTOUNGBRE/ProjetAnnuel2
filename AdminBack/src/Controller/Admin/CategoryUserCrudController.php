<?php

namespace App\Controller\Admin;

use App\Entity\CategoryUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


use Doctrine\ORM\EntityManagerInterface;

class CategoryUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategoryUser::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            BooleanField::new('active'),
            TextField::new('name'),
            
        ];
    } 

    public function deleteEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof CategoryUser) {
            return;
        }
    
        // Récupérer tous les utilisateurs associés à la catégorie utilisateur
    $users = $entityInstance->getUsers();

    // Supprimer chaque utilisateur
    foreach ($users as $user) {
        $manager->remove($user);
    }

    // Supprimer la catégorie utilisateur
    $manager->remove($entityInstance);

    // Exécuter les opérations en base de données
    $manager->flush();

    }
    
}
