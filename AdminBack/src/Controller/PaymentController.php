<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\ReservationVoyageur;
use App\Entity\User;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class PaymentController extends AbstractController
{
    private $logger;
    private $entityManager;
    private $serializer;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/pay', name: 'api_pay', methods: ['POST'])]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Log the received data
        $this->logger->info('Received data for createPaymentIntent: ' . json_encode($data));

        if (!isset($data['amount'], $data['paymentMethodId'])) {
            $this->logger->error('Invalid data: ' . json_encode($data));
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $amount = $data['amount'];
        $paymentMethodId = $data['paymentMethodId'];

        Stripe::setApiKey('sk_test_51PZbBZRs7Q74dLwZntG1wBo9y7G8kDHsWKuUH8gYZjfKeCfnMxaEAmh8PaScr9ax96Z7C7ma7ZFbbdM4787Waszl0099B3hix1');

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
            $this->logger->error('Stripe API error: ' . $e->getMessage());
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    #[Route('/payments', name: 'create_payment', methods: ['POST'])]
    public function createPayment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $this->logger->info('Received data for createPayment: ' . json_encode($data));
    
        if (!isset($data['amount'], $data['method'], $data['reservationId'])) {
            $this->logger->error('Invalid data: ' . json_encode($data));
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $payment = new Payment();
        $payment->setDate(new \DateTime());
        $payment->setAmount($data['amount']);
        $payment->setMethod($data['method']);
        $payment->setCardLast4($data['cardLast4'] ?? '');
        $payment->setFirstName($data['firstName'] ?? '');
        $payment->setLastName($data['lastName'] ?? '');
    
        $reservation = $this->entityManager->getRepository(ReservationVoyageur::class)->find($data['reservationId']);
        if (!$reservation) {
            $this->logger->error('Reservation not found for ID: ' . $data['reservationId']);
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $payment->setReservation($reservation);
    
        $proprietor = $this->entityManager->getRepository(User::class)->find($data['proprietorId']);
        if (!$proprietor) {
            $this->logger->error('Proprietor not found for ID: ' . $data['proprietorId']);
            return new JsonResponse(['error' => 'Proprietor not found'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $payment->setProprietor($proprietor);
    
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    
        $responseData = $this->serializer->serialize($payment, 'json', ['groups' => 'payment:read']);
        $this->logger->info('Payment created successfully: ' . json_encode($responseData));
        return new JsonResponse($responseData, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route('/payments/{id}', name: 'get_payment', methods: ['GET'])]
    public function getPayment(PaymentRepository $paymentRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $payment = $paymentRepository->find($id);

        if (!$payment) {
            return new JsonResponse(['error' => 'Payment not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $jsonPayment = $serializer->serialize($payment, 'json', ['groups' => 'payment:read']);

        return new JsonResponse($jsonPayment, 200, [], true);
    }

    #[Route('/payments', name: 'list_payments', methods: ['GET'])]
    public function listPayments(PaymentRepository $paymentRepository, SerializerInterface $serializer): JsonResponse
    {
        $payments = $paymentRepository->findAll();

        $jsonPayments = $serializer->serialize($payments, 'json', ['groups' => 'payment:read']);

        return new JsonResponse($jsonPayments, 200, [], true);
    }
}
