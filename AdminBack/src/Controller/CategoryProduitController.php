<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Entity\Category;


class CategoryProduitController extends AbstractController
{
    #[Route('/api/categories', name: 'get_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findBy(['active' => true]);

        $responseData = [];
        foreach ($categories as $category) {
            $responseData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'active' => $category->isActive(),
            ];
        }

        return new JsonResponse($responseData, 200);
    }
}
