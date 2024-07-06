<?php
// src/Controller/PropertyController.php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\PropertyRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class PropertyController extends AbstractController
{

    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/api/properties', name: 'get_properties', methods: ['GET'])]
    public function getProperties(PropertyRepository $propertyRepository, SerializerInterface $serializer): JsonResponse
    {
        $properties = $propertyRepository->findAll();
        $data = $serializer->serialize($properties, 'json', ['groups' => 'property:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
    #[Route('/api/properties', name: 'get_all_properties', methods: ['GET'])]
    public function getAllProperties(PropertyRepository $propertyRepository): JsonResponse
    {
        $properties = $propertyRepository->findAll();
        $data = [];

        foreach ($properties as $property) {
            $data[] = [
                'id' => $property->getId(),
                'name' => $property->getName(),
                'description' => $property->getDescription(),
                'image' => $property->getImage(),
                'commune' => $property->getCommune(),
            ];
        }

        return new JsonResponse($data);
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

            if ($file && $file->isValid()) {
                try {
                    $filename = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('property_photos_directory'), $filename);
                    $property->setImage($filename);
                } catch (InvalidArgumentException $e) {
                    $logger->error('File error: ' . $e->getMessage());
                    return new JsonResponse(['message' => 'Invalid file uploaded'], 400);
                }
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
        CategoryRepository $categoryRepository, // Ajout de la déclaration correcte
        ValidatorInterface $validator,
        SerializerInterface $serializer, // Ajout du serializer
        LoggerInterface $logger
    ): JsonResponse {
        try {
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
        } catch (\Exception $e) {
            $logger->error('An error occurred while updating the property', ['exception' => $e]);
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour de la propriété.'], 500);
        }
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

    #[Route('/api/search-properties', name: 'search_properties', methods: ['GET'])]
    public function searchProperties(Request $request, PropertyRepository $propertyRepository, SerializerInterface $serializer, LoggerInterface $logger): JsonResponse
    {
        $commune = $request->query->get('commune');
        $maxPersons = $request->query->get('maxPersons');

        if (!$commune) {
            $logger->error('Commune not provided');
            return new JsonResponse(['message' => 'Commune not provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $logger->info('Searching for properties in commune: ' . $commune . ' with max persons: ' . $maxPersons);
        
        $criteria = ['commune' => $commune];
        if ($maxPersons) {
            $properties = $propertyRepository->findByCommuneAndMaxPersons($commune, $maxPersons);
        } else {
            $properties = $propertyRepository->findBy(['commune' => $commune]);
        }

        if (empty($properties)) {
            $logger->info('No properties found for commune: ' . $commune);
        } else {
            $logger->info('Properties found: ' . count($properties));
        }

        $data = $serializer->serialize($properties, 'json', ['groups' => 'property:read']);
        
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

#[Route('/api/property-details/{id}', name: 'get_property_details', methods: ['GET'])]
public function getPropertyDetails(int $id, PropertyRepository $propertyRepository): JsonResponse
{
    $property = $propertyRepository->find($id);

    if (!$property) {
        return new JsonResponse(['message' => 'Propriété non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
    }

    $data = [
        'id' => $property->getId(),
        'name' => $property->getName(),
        'description' => $property->getDescription(),
        'image' => $property->getImage(),
        'commune' => $property->getCommune(),
        'price' => $property->getPrice(),
        'maxPersons' => $property->getMaxPersons(),
        'hasPool' => $property->HasPool(),
        'area' => $property->getArea(),
        'hasBalcony' => $property->HasBalcony(),
        'createdAt' => $property->getCreatedAt()->format('Y-m-d H:i:s'),
        'updatedAt' => $property->getUpdatedAt() ? $property->getUpdatedAt()->format('Y-m-d H:i:s') : null,
    ];

    return new JsonResponse($data);
}

#[Route('/api/properties/{id}/reservations', name: 'get_property_reservations', methods: ['GET'])]
public function getPropertyReservations(int $id, ReservationVoyageurRepository $reservationRepository): JsonResponse
{
    $reservations = $reservationRepository->findBy(['property' => $id]);

    return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
}
}
