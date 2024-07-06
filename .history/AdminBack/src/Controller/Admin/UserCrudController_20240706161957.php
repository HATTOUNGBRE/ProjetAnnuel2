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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class UserCrudController extends AbstractCrudController
{
    public const PROFILE_BASE_PATH = 'uploads/image/profiles';
    public const PROFILE_UPLOAD_DIR = 'public/uploads/image/profiles';
    private $entityManager;
    private $adminUrlGenerator;
    private $mailer;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->mailer = $mailer;
        $this->logger = $logger;
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

        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl();
        return $this->redirect($url);
    }

    public function banUser(AdminContext $context): RedirectResponse
    {
        $user = $context->getEntity()->getInstance();
        $user->setIsSuspended(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été banni avec succès.');

        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl();
        return $this->redirect($url);
    }

    public function sendEmail(AdminContext $context): Response
    {
        $this->logger->info('Starting to send emails to all users.');
    
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $this->logger->info('Found ' . count($users) . ' users to send emails to.');
    
        foreach ($users as $user) {
            $email = (new Email())
                ->from('hello.teampcs@outlook.com')
                ->to($user->getEmail())
                ->subject('Information importante')
                ->text('Ceci est un email envoyé à tous les utilisateurs.');
    
            try {
                $this->mailer->send($email);
                $this->logger->info('Email sent to ' . $user->getEmail());
            } catch (TransportExceptionInterface $e) {
                $this->logger->error('Erreur lors de l\'envoi de l\'email à ' . $user->getEmail() . ': ' . $e->getMessage());
                $this->addFlash('danger', 'Erreur lors de l\'envoi de l\'email à ' . $user->getEmail());
            }
        }
    
        $this->addFlash('success', 'Emails envoyés à tous les utilisateurs avec succès.');
    
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->setAction(Crud::PAGE_INDEX)->generateUrl();
        return $this->redirect($url);
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
            BooleanField::new('verified')->renderAsSwitch(false),
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

    public function onMessageSent(MessageEvent $event)
{
    $message = $event->getMessage();

    if ($message instanceof Email) {
        $toAddresses = array_map(fn($address) => $address->getAddress(), $message->getTo());
        $logEntry = sprintf(
            "[%s] Email sent to: %s\n",
            date('Y-m-d H:i:s'),
            implode(', ', $toAddresses)
        );

        // Chemin vers le fichier de log
        $logFilePath = __DIR__ . '/../../var/log/mail.log';

        // Ecrire dans le fichier de log
        file_put_contents($logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
    }}
    
}
