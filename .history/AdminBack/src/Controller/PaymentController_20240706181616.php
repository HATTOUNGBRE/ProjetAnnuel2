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
    public function createPaymentIntent(Request $request): JsonResponse
    {
        Stripe::setApiKey('sk_test_51PZbBZRs7Q74dLwZntG1wBo9y7G8kDHsWKuUH8gYZjfKeCfnMxaEAmh8PaScr9ax96Z7C7ma7ZFbbdM4787Waszl0099B3hix1');

        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];
        $paymentMethodId = $data['paymentMethodId'];

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe expects the amount in cents
                'currency' => 'eur',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            return new JsonResponse(['paymentIntent' => $paymentIntent]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
