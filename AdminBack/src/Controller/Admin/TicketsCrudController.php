<?php

namespace App\Controller\Admin;

use App\Entity\Tickets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TicketsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tickets::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('question', 'question'),
            TextEditorField::new('message', 'message'),
            ChoiceField::new('status', 'Status')
                ->setChoices([
                    'Nouveau' => Tickets::STATUS_NEW,
                    'En cours' => Tickets::STATUS_IN_PROGRESS,
                    'Résolu' => Tickets::STATUS_RESOLVED,
                    'Fermé' => Tickets::STATUS_CLOSED,
                ]),
            ChoiceField::new('priority', 'Priority')
                ->setChoices([
                    'Low' => Tickets::PRIORITY_LOW,
                    'Medium' => Tickets::PRIORITY_MEDIUM,
                    'High' => Tickets::PRIORITY_HIGH,
                ]),
            DateTimeField::new('created_at', 'Created At'),
            DateTimeField::new('updated_at', 'Updated At')->hideOnForm(),
            TextField::new('ticketNumber', 'Ticket Number'),
            TextField::new('assigned_to', 'Assigned To')->hideOnForm(),
        ];
    }
}
