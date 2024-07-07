<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\UserRepository;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaymentRepository;
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

    #[Route('/api/payments', name: 'create_payment', methods: ['POST'])]
    public function createPayment(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['amount'], $data['method'], $data['proprioId'])) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $proprietor = $userRepository->find($data['proprioId']);
        if (!$proprietor) {
            return new JsonResponse(['error' => 'Proprietor not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $payment = new Payment();
        $payment->setDate(new \DateTime());
        $payment->setAmount($data['amount']);
        $payment->setMethod($data['method']);
        $payment->setProprietor($proprietor);

        $entityManager->persist($payment);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Payment created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/payments/{id}', name: 'get_payment', methods: ['GET'])]
    public function getPayment(PaymentRepository $paymentRepository, int $id): JsonResponse
    {
        $payment = $paymentRepository->find($id);

        if (!$payment) {
            return new JsonResponse(['error' => 'Payment not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($payment);
    }

    #[Route('/api/payments', name: 'list_payments', methods: ['GET'])]
    public function listPayments(PaymentRepository $paymentRepository): JsonResponse
    {
        $payments = $paymentRepository->findAll();

        return new JsonResponse($payments);
    }
}
