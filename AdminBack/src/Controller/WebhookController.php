<?php
// src/Controller/WebhookController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends AbstractController
{
    /**
     * @Route("/api/webhook", name="stripe_webhook", methods={"POST"})
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $endpoint_secret = 'your-webhook-signing-secret';

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event['type'];
        }

        return new Response('', 200);
    }
}
