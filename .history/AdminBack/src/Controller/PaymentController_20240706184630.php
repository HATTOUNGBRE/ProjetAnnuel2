<?php
// src/Controller/PaymentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends AbstractController
{
    /**
     * @Route("/api/create-payment-intent", name="create_payment_intent", methods={"POST"})
     */
    #[Route('/api/pay', name: 'api_pay', methods: ['POST'])]
    public function pay(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['amount'], $data['paymentMethodId'])) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid request data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        Stripe::setApiKey('sk_test_51PZbBZRs7Q74dLwZ8oJkQ6tGojmjxk0IYdhftOmyZjvsdfszrYdH3ACqNrtLUQQ7KsO2VVZ5mG6EdFOT6sgKsD00OVox3VQZ');

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $data['amount'],
                'currency' => 'eur',
                'payment_method' => $data['paymentMethodId'],
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            return new JsonResponse(['success' => true, 'paymentIntent' => $paymentIntent]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
