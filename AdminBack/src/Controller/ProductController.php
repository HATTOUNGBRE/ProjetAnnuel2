<?
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class ProductController extends AbstractController
{
    #[Route('/api/products/{userId}', name: 'user_products', methods: ['GET'])]
    public function getUserProducts(int $userId, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findBy(['proprio' => $userId]);
        $data = $serializer->serialize($products, 'json', ['groups' => 'product:read']);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = $request->request->all();
        $file = $request->files->get('image');

        $proprioId = $data['proprio'] ?? null;
        $proprio = $entityManager->getRepository(User::class)->find($proprioId);

        if (!$proprio || $proprio->getCategoryUser()->getId() !== 1) {
            return new JsonResponse(['message' => 'Vous n\'êtes pas autorisé à ajouter une propriété.'], 403);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice((float) $data['price']);
        $product->setCategory($entityManager->getRepository(Category::class)->find($data['category']));
        $product->setProprio($proprio);
        $product->setActive(true); // Par défaut, la propriété est active
        $product->setCreatedAt(new \DateTimeImmutable());

        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $filename);
            $product->setImage($filename);
        }

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        $responseData = $serializer->serialize($product, 'json', ['groups' => 'product:read']);
        return new JsonResponse($responseData, 201, [], true);
    }


    // MODIFIER 
    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
public function update(
    int $id,
    Request $request,
    EntityManagerInterface $entityManager,
    ProductRepository $productRepository,
    CategoryRepository $categoryRepository,
    ValidatorInterface $validator,
    LoggerInterface $logger
): JsonResponse {
    $product = $productRepository->find($id);

    if (!$product) {
        return new JsonResponse(['message' => 'Produit non trouvé.'], 404);
    }

    $rawContent = $request->getContent();
    $logger->info('Raw content: ' . $rawContent);

    $data = json_decode($rawContent, true);
    $logger->info('Received data for update:', $data);

    if (is_null($data)) {
        return new JsonResponse(['message' => 'Données invalides.'], 400);
    }

    $product->setName($data['name'] ?? $product->getName());
    $product->setDescription($data['description'] ?? $product->getDescription());
    $product->setPrice((float)($data['price'] ?? $product->getPrice()));
    $product->setCategory($categoryRepository->find($data['category'] ?? $product->getCategory()->getId()));

    $errors = $validator->validate($product);
    if (count($errors) > 0) {
        $errorsString = (string) $errors;
        return new JsonResponse(['message' => $errorsString], 400);
    }

    $entityManager->persist($product);
    $entityManager->flush();

    $responseData = [
        'id' => $product->getId(),
        'name' => $product->getName(),
        'description' => $product->getDescription(),
        'price' => $product->getPrice(),
        'category' => $product->getCategory()->getId(),
    ];

    $logger->info('Updated product:', $responseData);

    return new JsonResponse($responseData, 200);
}
    


    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function delete(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Produit non trouvé.'], 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Produit supprimé avec succès.'], 200);
    }
}
