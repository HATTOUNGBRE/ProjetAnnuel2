<?php
// src/Controller/PropertyController.php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class PropertyController extends AbstractController
{
    #[Route('/api/properties', name: 'get_properties', methods: ['GET'])]
    public function getProperties(PropertyRepository $propertyRepository, SerializerInterface $serializer): JsonResponse
    {
        $properties = $propertyRepository->findAll();
        $data = $serializer->serialize($properties, 'json', ['groups' => 'property:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
    
    #[Route('/api/properties/{userId}', name: 'user_properties', methods: ['GET'])]
    public function getUserProperties(int $userId, PropertyRepository $propertyRepository, SerializerInterface $serializer): JsonResponse
    {
        $properties = $propertyRepository->findBy(['proprio' => $userId]);
        $data = $serializer->serialize($properties, 'json', ['groups' => 'property:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/properties', name: 'create_property', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $data = $request->request->all();
            $file = $request->files->get('image');

            // Validate required fields
            if (empty($data['proprio'])) {
                $logger->error('Proprio ID is missing in the request data', $data);
                return new JsonResponse(['message' => 'Proprio ID is missing'], 400);
            }

            if (empty($data['category'])) {
                $logger->error('Category ID is missing in the request data');
                return new JsonResponse(['message' => 'Category ID is missing'], 400);
            }

            $logger->info('Request data: ', $data);

            $proprioId = $data['proprio'];
            $logger->info('Proprio ID: ' . $proprioId);

            $proprio = $entityManager->getRepository(User::class)->find($proprioId);
            if (!$proprio) {
                $logger->error('Proprio not found for ID: ' . $proprioId);
                return new JsonResponse(['message' => 'Propriétaire non trouvé.'], 404);
            }

            if ($proprio->getCategoryUser()->getId() !== 1) {
                return new JsonResponse(['message' => 'Vous n\'êtes pas autorisé à ajouter une propriété.'], 403);
            }

            $category = $entityManager->getRepository(Category::class)->find($data['category']);
            if (!$category) {
                $logger->error('Category not found for ID: ' . $data['category']);
                return new JsonResponse(['message' => 'Catégorie non trouvée.'], 404);
            }

            // Create and populate Property entity
            $property = new Property();
            $property->setName($data['name'] ?? '');
            $property->setDescription($data['description'] ?? '');
            $property->setPrice((float) ($data['price'] ?? 0));
            $property->setCategory($category);
            $property->setProprio($proprio);
            $property->setActive(true);
            $property->setCreatedAt(new \DateTimeImmutable());
            $property->setMaxPersons((int) ($data['maxPersons'] ?? 0));
            $property->setHasPool((bool) ($data['hasPool'] ?? false));
            $property->setArea((float) ($data['area'] ?? 0));
            $property->setHasBalcony((bool) ($data['hasBalcony'] ?? false));
            $property->setCommune($data['commune'] ?? '');

            if ($file) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('property_photos_directory'), $filename);
                $property->setImage($filename);
            } else {
                // Set default image if none provided
                $property->setImage('default_appart.jpg');
            }

            $errors = $validator->validate($property);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return new JsonResponse(['message' => $errorsString], 400);
            }

            $entityManager->persist($property);
            $entityManager->flush();

            $responseData = $serializer->serialize($property, 'json', ['groups' => 'property:read']);
            return new JsonResponse($responseData, 201, [], true);

        } catch (\Exception $e) {
            $logger->error('An error occurred while creating the property', ['exception' => $e]);
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création de la propriété.'], 500);
        }
    }


    #[Route('/api/properties/{id}', name: 'update_property', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        PropertyRepository $propertyRepository,
        CategoryRepository $categoryRepository,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ): JsonResponse {
        $property = $propertyRepository->find($id);

        if (!$property) {
            return new JsonResponse(['message' => 'Propriété non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (is_null($data)) {
            return new JsonResponse(['message' => 'Données invalides.'], 400);
        }

        $property->setName($data['name'] ?? $property->getName());
        $property->setDescription($data['description'] ?? $property->getDescription());
        $property->setPrice((float)($data['price'] ?? $property->getPrice()));
        $property->setCategory($categoryRepository->find($data['category'] ?? $property->getCategory()->getId()));
        $property->setMaxPersons((int) ($data['maxPersons'] ?? $property->getMaxPersons()));
        $property->setHasPool((bool) ($data['hasPool'] ?? $property->hasPool()));
        $property->setArea((float) ($data['area'] ?? $property->getArea()));
        $property->setHasBalcony((bool) ($data['hasBalcony'] ?? $property->hasBalcony()));

        $errors = $validator->validate($property);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($property);
        $entityManager->flush();

        $responseData = $serializer->serialize($property, 'json', ['groups' => 'property:read']);
        return new JsonResponse($responseData, 200, [], true);
    }

    #[Route('/api/properties/{id}', name: 'delete_property', methods: ['DELETE'])]
    public function delete(int $id, PropertyRepository $propertyRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $property = $propertyRepository->find($id);

        if (!$property) {
            return new JsonResponse(['message' => 'Propriété non trouvée.'], 404);
        }

        $entityManager->remove($property);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Propriété supprimée avec succès.'], 200);
    }
}
