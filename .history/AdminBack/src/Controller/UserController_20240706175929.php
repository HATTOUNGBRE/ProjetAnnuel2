<?php 

namespace App\Controller;

use App\Entity\User;
use App\Entity\CategoryUser; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class UserController extends AbstractController
{
    private $entityManager;
    private $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

  

    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository, // Add UserRepository here
        MailerInterface $mailer // Add MailerInterface here
    ): Response {
        $data = $request->request->all();
        $imageProfile = $request->files->get('imageProfile');

        $name = $data['name'] ?? '';
        $surname = $data['surname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $categoryUserId = $data['categoryUserId'] ?? '';

        // Check if email already exists
        $existingUser = $userRepository->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Email already in use'], Response::HTTP_CONFLICT);
        }

        $filesystem = new Filesystem();

        if ($imageProfile) {
            $originalFilename = pathinfo($imageProfile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageProfile->guessExtension();

            try {
                // Move the uploaded file to the profile photos directory
                $profilePhotosDir = $this->getParameter('profile_photos_directory');
                $imageProfile->move($profilePhotosDir, $newFilename);
                $imageProfilePath = $newFilename;

                // Copy the file to the front-end directory
                $frontendUploadDir = $this->getParameter('frontend_upload_directory');
                $filesystem->copy($profilePhotosDir . '/' . $newFilename, $frontendUploadDir . '/' . $newFilename);
            } catch (FileException | IOExceptionInterface $e) {
                return new JsonResponse(['message' => 'Failed to upload or copy profile image: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $imageProfilePath = null;
        }

        $user = new User();
        $user->setName($name);
        $user->setSurname($surname);
        $user->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password); // Hash the password
        $user->setPassword($hashedPassword);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setisVerified(false);

        // Retrieve CategoryUser entity
        $categoryUser = $entityManager->getRepository(CategoryUser::class)->find($categoryUserId);
        if (!$categoryUser) {
            return new JsonResponse(['message' => 'Invalid category user ID'], Response::HTTP_BAD_REQUEST);
        }
        $user->setCategoryUser($categoryUser);
        $user->setImageProfile($imageProfilePath);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        // Send welcome email
        $emailMessage = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($user->getEmail())
            ->subject('Welcome to Our Platform')
            ->text("Dear {$user->getName()},\n\nThank you for registering on our platform.\n\nBest regards,\nTeam PCS");

        $mailer->send($emailMessage);

        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_OK);
    }

    #[Route('/api/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'No user found for id ' . $id], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'is_admin' => $user->isVerified(),
            'image_profile' => $user->getImageProfile(),
            'category_user' => $user->getCategoryUser() ? $user->getCategoryUser()->getId() : null,
        ]);
    }

    #[Route('/api/user/{id}/update', name: 'update_user', methods: ['POST'])]
public function update(
    Request $request,
    ValidatorInterface $validator,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager,
    UserRepository $userRepository,
    int $id
): Response {
    $user = $userRepository->find($id);
    if (!$user) {
        return new JsonResponse(['message' => 'No user found for id ' . $id], Response::HTTP_NOT_FOUND);
    }

    $data = $request->request->all();
    $imageProfile = $request->files->get('imageProfile');

    $name = $data['name'] ?? $user->getName();
    $surname = $data['surname'] ?? $user->getSurname();
    $email = $data['email'] ?? $user->getEmail();
    $password = $data['password'] ?? '';
    $categoryUserId = $data['categoryUserId'] ?? $user->getCategoryUser()->getId();

    $filesystem = new Filesystem();

    if ($imageProfile) {
        $originalFilename = pathinfo($imageProfile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageProfile->guessExtension();

        try {
            // Move the uploaded file to the profile photos directory
            $profilePhotosDir = $this->getParameter('profile_photos_directory');
            $imageProfile->move($profilePhotosDir, $newFilename);
            $imageProfilePath = $newFilename;

            // Copy the file to the front-end directory
            $frontendUploadDir = $this->getParameter('frontend_upload_directory');
            $filesystem->copy($profilePhotosDir . '/' . $newFilename, $frontendUploadDir . '/' . $newFilename);
        } catch (FileException | IOExceptionInterface $e) {
            return new JsonResponse(['message' => 'Failed to upload or copy profile image: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } else {
        $imageProfilePath = $user->getImageProfile();
    }

    $user->setName($name);
    $user->setSurname($surname);
    $user->setEmail($email);
    if (!empty($password)) {
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }
    // Retrieve CategoryUser entity
    $categoryUser = $entityManager->getRepository(CategoryUser::class)->find($categoryUserId);
    if (!$categoryUser) {
        return new JsonResponse(['message' => 'Invalid category user ID'], Response::HTTP_BAD_REQUEST);
    }
    $user->setCategoryUser($categoryUser);
    $user->setImageProfile($imageProfilePath);

    $errors = $validator->validate($user);

    if (count($errors) > 0) {
        $errorsString = (string) $errors;
        return new JsonResponse(['message' => $errorsString], Response::HTTP_BAD_REQUEST);
    }

    $entityManager->persist($user);
    $entityManager->flush();

    return new JsonResponse(['message' => 'User updated successfully'], Response::HTTP_OK);
}

#[Route('/api/voyageurs/{id}', name: 'get_voyageur_details', methods: ['GET'])]
public function getVoyageurDetails(int $id, UserRepository $userRepository): JsonResponse
{
    $voyageur = $userRepository->find($id);

    if (!$voyageur) {
        return new JsonResponse(['message' => 'No voyageur found for id ' . $id], JsonResponse::HTTP_NOT_FOUND);
    }

    return new JsonResponse([
        'id' => intval($voyageur->getId()),
        'name' => $voyageur->getName(),
        'surname' => $voyageur->getSurname(),
        'email' => $voyageur->getEmail(),
        'created_at' => $voyageur->getCreatedAt()->format('Y-m-d H:i:s'),
        'is_admin' => $voyageur->isVerified(),
        'image_profile' => $voyageur->getImageProfile(),
        'category_user' => $voyageur->getCategoryUser() ? $voyageur->getCategoryUser()->getId() : null,
    ]);
}
}
