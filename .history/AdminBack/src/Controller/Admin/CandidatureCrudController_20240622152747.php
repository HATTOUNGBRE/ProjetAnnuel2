<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class CandidatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidature::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('prestataire', 'Prestataire'),
            AssociationField::new('reservation', 'Réservation'),
            BooleanField::new('validated', 'Validé'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $validate = Action::new('validate', 'Valider')
            ->linkToCrudAction('validateCandidature');

        return $actions
            ->add(Crud::PAGE_INDEX, $validate);
    }

    public function validateCandidature(AdminContext $context)
    {
        $candidature = $context->getEntity()->getInstance();
        $candidature->setValidated(true);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Candidature validée avec succès');

        return $this->redirect($this->generateUrl('admin'));
    }
}
