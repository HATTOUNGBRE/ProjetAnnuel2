<?
namespace App\Controller;

use App\Entity\Property;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PropertyController extends AbstractController
{
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
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $proprioId = $data['proprio'] ?? null;
        $proprio = $entityManager->getRepository(User::class)->find($proprioId);

        if (!$proprio || $proprio->getCategoryUser()->getId() !== 1) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas autorisé à ajouter une propriété.'], 403);
        }

        $property = new Property();
        $property->setName($data['name']);
        $property->setDescription($data['description']);
        $property->setPrice((float) $data['price']);
        $property->setCategory($entityManager->getRepository(Category::class)->find($data['category']));
        $property->setProprio($proprio);
        $property->setActive(true);
        $property->setCreatedAt(new \DateTimeImmutable());
        $property->setMaxPersons((int) $data['maxPersons']);
        $property->setHasPool((bool) $data['hasPool']);
        $property->setArea((int) $data['area']);
        $property->setHasBalcony((bool) $data['hasBalcony']);

        // Handle image upload if necessary

        $errors = $validator->validate($property);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($property);
        $entityManager->flush();

        $responseData = $serializer->serialize($property, 'json', ['groups' => 'property:read']);
        return new JsonResponse($responseData, 201, [], true);
    }

    #[Route('/api/properties/{id}', name: 'update_property', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        PropertyRepository $propertyRepository,
        CategoryRepository $categoryRepository,
        ValidatorInterface $validator
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
        $property->setArea((int) ($data['area'] ?? $property->getArea()));
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
