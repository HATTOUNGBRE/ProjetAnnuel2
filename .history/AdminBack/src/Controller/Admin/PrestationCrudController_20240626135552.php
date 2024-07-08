<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre'),
            TextEditorField::new('description', 'Description'),
            ChoiceField::new('type', 'Type')->setChoices([
                'Ménage' => 'ménage',
                'Électricité' => 'électricité',
                'Plomberie' => 'plomberie',
                'Taxi' => 'taxi',
                'Coursier' => 'coursier',
                'Gardien' => 'gardien',
                'Jardinage' => 'jardinage',
                'Réparation' => 'réparation',
                'Blanchisserie' => 'blanchisserie',
            ]),
            DateTimeField::new('dateDeCreation', 'Date de Création')->hideOnForm(),
            DateTimeField::new('dateDEffet', 'Date d\'Effet'),
            DateTimeField::new('dateDeFin', 'Date de Fin'),
            TextField::new('statut', 'Statut')->hideOnForm(),
            BooleanField::new('active', 'Active'),
            AssociationField::new('user', 'Utilisateur')
                ->setCrudController(UserCrudController::class)
                ->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        $entityInstance->setDateDeCreation(new \DateTime());
        $entityInstance->setStatut('En attente');

        $manager->persist($entityInstance);
        $manager->flush();
    }

    public function updateEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        parent::updateEntity($manager, $entityInstance);
    }

    /**
     * @Route("/admin/prestation/{id}", name="admin_prestation_detail", methods={"GET"})
     */
    public function detail(Request $request, EntityManagerInterface $manager, $id): Response
    {
        $prestation = $manager->getRepository(Prestation::class)->find($id);

        if (!$prestation) {
            throw new NotFoundHttpException('La prestation demandée n\'existe pas.');
        }

        return $this->render('admin/prestation/detail.html.twig', [
            'prestation' => $prestation,
        ]);
    }
}
