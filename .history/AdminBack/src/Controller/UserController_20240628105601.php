<?php 

namespace App\Controller;

use App\Entity\User;
use App\Entity\CategoryUser; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        EntityManagerInterface $entityManager
    ): Response {
        $data = $request->request->all();
        $imageProfile = $request->files->get('imageProfile');

        $name = $data['name'] ?? '';
        $surname = $data['surname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $categoryUserId = $data['categoryUserId'] ?? '';

        $filesystem = new Filesystem();

        if ($imageProfile) {
            $originalFilename = pathinfo($imageProfile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageProfile->guessExtension();

            try {
                $imageProfile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $imageProfilePath = $newFilename;

                // Copier le fichier dans le dossier front-end
                $frontendUploadDir = $this->getParameter('frontend_upload_directory');
                $filesystem->copy($this->getParameter('profile_photos_web_path') . '/' . $newFilename, $frontendUploadDir . '/' . $newFilename);
            } catch (FileException | IOExceptionInterface $e) {
                return new JsonResponse(['message' => 'Failed to upload or copy profile image'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $user->setIsAdmin(false);

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
            'is_admin' => $user->isAdmin(),
            'image_profile' => $user->getImageProfile(),
            'category_user' => $user->getCategoryUser() ? $user->getCategoryUser()->getId() : null,
        ]);
    }
}
