<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            BooleanField::new('active'),
            TextField::new('name'),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),

        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
       if( !$entityInstance instanceOf Category) return;

       $entityInstance->setCreatedAt(new \DateTimeImmutable());
       parent::persistEntity($manager, $entityInstance); // permet de persister l'entité

    }

    public function deleteEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if( !$entityInstance instanceOf Category) return;

        foreach($entityInstance->getProperty() as $property){
            $manager->remove($property);
        }

        parent::deleteEntity($manager, $entityInstance); // permet de supprimer l'entité
    }
 
}
