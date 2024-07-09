<?php
// src/Controller/Admin/TicketCrudController.php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ticket')
            ->setEntityLabelInPlural('Tickets')
            ->setSearchFields(['ticketNumber', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('ticketNumber')->setLabel('Numéro de Ticket'),
            TextField::new('name')->setLabel('Nom'),
            TextField::new('surname')->setLabel('Prénom'),
            TextField::new('email')->setLabel('Email'),
            ChoiceField::new('role')->setLabel('Rôle')->setChoices([
                'Voyageur' => 'voyageur',
                'Propriétaire' => 'proprietaire',
                'Prestataire' => 'prestataire',
            ]),
            TextareaField::new('message')->setLabel('Message'),
            ChoiceField::new('status')->setLabel('Statut')->setChoices([
                'Ouvert' => 'ouvert',
                'En cours' => 'en_cours',
                'Résolu' => 'resolu',
                'Fermé' => 'ferme',
            ]),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
