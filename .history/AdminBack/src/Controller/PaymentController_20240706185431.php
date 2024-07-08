<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    #[Route('/api/pay', name: 'api_pay', methods: ['POST'])]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];
        $paymentMethodId = $data['paymentMethodId'];

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => 'http://localhost:5173/payment-success', // URL de retour après le paiement
            ]);

            return new JsonResponse(['success' => true, 'paymentIntent' => $paymentIntent]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
