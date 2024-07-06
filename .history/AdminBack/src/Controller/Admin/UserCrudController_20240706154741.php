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

class UserCrudController extends AbstractCrudController
{
    public const PROFILE_BASE_PATH = 'uploads/image/profiles';
    public const PROFILE_UPLOAD_DIR = 'public/uploads/image/profiles';
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
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

        $sendEmailsAction = Action::new('sendEmails', 'Envoyer Email')
            ->linkToCrudAction('sendEmails');

        return $actions
            ->add(Crud::PAGE_INDEX, $suspendUserAction)
            ->add(Crud::PAGE_INDEX, $banUserAction)
            ->add(Crud::PAGE_INDEX, $sendEmailsAction);
    }

    public function suspendUser(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $user->setIsSuspended(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été suspendu avec succès.');

        return $this->redirect($this->get(AdminUrlGenerator::class)->setController(UserCrudController::class)->generateUrl());
    }

    public function banUser(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $user->setIsSuspended(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été banni avec succès.');

        return $this->redirect($this->get(AdminUrlGenerator::class)->setController(UserCrudController::class)->generateUrl());
    }

    public function sendEmails(): RedirectResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $email = (new Email())
                ->from('your-email@example.com')
                ->to($user->getEmail())
                ->subject('Important Update')
                ->text('This is an important update for all users.');

            $this->mailer->send($email);
        }

        $this->addFlash('success', 'Emails envoyés à tous les utilisateurs avec succès.');

        return $this->redirect($this->get(AdminUrlGenerator::class)->setController(UserCrudController::class)->generateUrl());
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
