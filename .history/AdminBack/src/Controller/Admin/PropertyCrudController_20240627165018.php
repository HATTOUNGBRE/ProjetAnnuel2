<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\ORM\QueryBuilder;

class PropertyCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = 'duplicate';
    public const PROPERTIES_BASE_PATH = '.public/uploads/property_photos';
    public const PROPERTIES_UPLOAD_DIR = 'uploads/property_photos';

    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateProperty');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicate)
            ->add(Crud::PAGE_EDIT, $duplicate);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('commune'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            ImageField::new('image')
                ->setUploadDir(self::PROPERTIES_UPLOAD_DIR)
                ->setBasePath(self::PROPERTIES_BASE_PATH)
                ->setSortable(false),
            BooleanField::new('active'),
            IntegerField::new('maxPersons', 'Nombre de personnes max'),
            BooleanField::new('hasPool', 'Piscine'),
            IntegerField::new('area', 'Superficie (m²)'),
            BooleanField::new('hasBalcony', 'Terrasse/Balcon'),
            AssociationField::new('category')->setQueryBuilder(function(QueryBuilder $qb){
                $qb->where('entity.active = true');
            }),
            AssociationField::new('proprio')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    // Filtre les utilisateurs pour ne montrer que ceux ayant une categoryUser avec l'id de 1
                    $qb->join('entity.categoryUser', 'c')
                       ->where('c.id = :categoryId')
                       ->setParameter('categoryId', 1);
                })
                ->setFormTypeOption('choice_label', function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                }),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Property) return;

        $entityInstance->setCreatedAt(new \DateTimeImmutable()); // Définit la date de création

        parent::persistEntity($manager, $entityInstance);
    }

    public function duplicateProperty(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $manager): Response
    {
        /** @var Property $property */
        $property = $context->getEntity()->getInstance();
        $newProperty = clone $property;

        parent::persistEntity($manager, $newProperty);

        $url = $adminUrlGenerator
            ->setController(PropertyCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($newProperty->getId())
            ->generateUrl();

        return $this->redirect($url);
    }
}
