<?php
namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserCrudController extends AbstractCrudController
{
    public const PROFILE_BASE_PATH = 'uploads/image/profiles';
    public const PROFILE_UPLOAD_DIR = 'public/uploads/image/profiles';

    private $entityManager;
    private $mailer;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $suspendUserAction = Action::new('suspendUser', 'Suspendre')
            ->linkToCrudAction('suspendUser');

        $banUserAction = Action::new('banUser', 'Bannir')
            ->linkToCrudAction('banUser');

        $sendEmailAction = Action::new('sendEmail', 'Envoyer Email')
            ->linkToCrudAction('sendEmail');

        return $actions
            ->add(Crud::PAGE_INDEX, $suspendUserAction)
            ->add(Crud::PAGE_INDEX, $banUserAction)
            ->add(Crud::PAGE_INDEX, $sendEmailAction);
    }

    public function suspendUser(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $user->setIsSuspended(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été suspendu avec succès.');

        return $this->redirect($this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl());
    }

    public function banUser(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $user->setIsSuspended(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été banni avec succès.');

        return $this->redirect($this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl());
    }

    public function sendEmail(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $email = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($user->getEmail())
            ->subject('Important Information')
            ->text('Dear ' . $user->getName() . ',\n\nThis is an important message.\n\nBest regards,\nTeam');

        try {
            $this->mailer->send($email);
            $this->addFlash('success', 'Email envoyé avec succès à ' . $user->getEmail());
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('danger', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        return $this->redirect($this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('surname'),
            TextField::new('email'),
            TextField::new('password')->hideOnIndex(),
            ImageField::new('imageProfile')
                ->setUploadDir(self::PROFILE_UPLOAD_DIR)
                ->setBasePath(self::PROFILE_BASE_PATH)
                ->setSortable(false),
            BooleanField::new('isAdmin')->renderAsSwitch(false),
            AssociationField::new('categoryUser'),
            DateTimeField::new('created_at')->hideOnForm(),
            BooleanField::new('isSuspended', 'Suspendre')->renderAsSwitch(),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) return;

        $entityInstance->setCreatedAt(new \DateTimeImmutable());
        parent::persistEntity($manager, $entityInstance);
    }
}
