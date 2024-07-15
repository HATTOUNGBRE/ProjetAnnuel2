<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/admin/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $this->logger->info('Login page accessed');

        // Si l'utilisateur est déjà authentifié, redirigez-le vers /admin
        if ($this->getUser()) {
            $this->logger->info('User already authenticated, redirecting to admin_dashboard');
            return $this->redirectToRoute('admin_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $this->logger->info('Rendering login page', [
            'last_username' => $lastUsername,
            'error' => $error ? $error->getMessage() : 'No error'
        ]);

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/logout', name: 'admin_logout')]
    public function logout(): void
    {
        $this->logger->info('Logout route accessed');
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/admin/login_check', name: 'admin_login_check')]
    public function loginCheck(): void
    {
        $this->logger->info('Login check route accessed');
        // This code is never executed.
    }
}
