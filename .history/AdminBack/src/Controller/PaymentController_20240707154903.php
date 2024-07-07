<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/api/payments', name: 'list_payments', methods: ['GET'])]
    public function listPayments(PaymentRepository $paymentRepository): JsonResponse
    {
        $payments = $paymentRepository->findAll();
        $responseData = $this->serializer->serialize($payments, 'json', ['groups' => 'payment:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/payments/{id}', name: 'get_payment', methods: ['GET'])]
    public function getPayment(PaymentRepository $paymentRepository, int $id): JsonResponse
    {
        $payment = $paymentRepository->find($id);
        if (!$payment) {
            return new JsonResponse(['error' => 'Payment not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $responseData = $this->serializer->serialize($payment, 'json', ['groups' => 'payment:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/payments', name: 'create_payment', methods: ['POST'])]
    public function createPayment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['amount'], $data['method'], $data['reservationId'], $data['proprietorId'])) {
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
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $payment->setReservation($reservation);

        $proprietor = $this->entityManager->getRepository(User::class)->find($data['proprietorId']);
        if (!$proprietor) {
            return new JsonResponse(['error' => 'Proprietor not found'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $payment->setProprietor($proprietor);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($payment, 'json', ['groups' => 'payment:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_CREATED, [], true);
    }
}
