<?php

// src/Controller/Admin/PropertyCrudController.php

namespace App\Controller\Admin;

use App\Entity\Property;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Property')
            ->setEntityLabelInPlural('Properties')
            ->setSearchFields(['name', 'description', 'commune'])
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('description'),
            NumberField::new('price'),
            NumberField::new('maxPersons'),
            BooleanField::new('hasPool'),
            NumberField::new('area'),
            BooleanField::new('hasBalcony'),
            TextField::new('commune'),
            ImageField::new('image')
                ->setBasePath($this->getParameter('property_photos_web_path'))
                ->setUploadDir($this->getParameter('property_photos_directory'))
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
}
