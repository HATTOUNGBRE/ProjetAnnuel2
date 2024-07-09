// src/components/PaymentPage.jsx

import React from 'react';
import { useLocation } from 'react-router-dom';
import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js';
import { useNavigate } from 'react-router-dom';

const PaymentPage = () => {
  const stripe = useStripe();
  const elements = useElements();
  const location = useLocation();
  const navigate = useNavigate();
  const { dateArrivee, dateDepart, guestNb, propertyId, name, surname, voyageurId, totalPrice } = location.state;

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!stripe || !elements) {
        return;
    }

    const cardElement = elements.getElement(CardElement);

    const { error, paymentMethod } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
    });

    if (error) {
        console.error(error);
    } else {
        console.log('PaymentMethod:', paymentMethod);
        try {
            // Step 1: Create a payment intent with Stripe
            const response = await fetch('http://localhost:8000/api/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: totalPrice * 100, // Montant en centimes
                    paymentMethodId: paymentMethod.id,
                    cardLast4: paymentMethod.card.last4,
                    firstName: name,
                    lastName: surname,
                    reservationId: voyageurId,
                    method: 'credit_card',
                }),
            });

            const data = await response.json();

            if (data.success) {
                // Step 2: Save payment in database
                const savePaymentResponse = await fetch('http://localhost:8000/api/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: totalPrice * 100, // Montant en centimes
                        method: 'credit_card',
                        reservationId: 2, // Utilisez l'ID de la réservation approprié
                        cardLast4: paymentMethod.card.last4,
                        firstName: name,
                        lastName: surname,
                       
                    }),
                });

                const savePaymentData = await savePaymentResponse.json();
                if (savePaymentResponse.ok) {
                    alert('Payment successful and saved in the database!');
                    navigate('/payment-success'); // Redirigez vers une page de succès si nécessaire
                } else {
                    console.error('Error saving payment:', savePaymentData);
                    alert('Payment succeeded but failed to save in the database. Please contact support.');
                }
            } else {
                alert('Payment failed. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Payment failed. Please try again.');
        }
    }
};


  return (
    <div className="container mx-auto p-10">
      <h2 className="text-2xl font-semibold mb-4">Payment</h2>
      <p className="mb-4">Total Price: {totalPrice} €</p>
      <form onSubmit={handleSubmit}>
        <CardElement className="mb-4" />
        <button
          type="submit"
          disabled={!stripe}
          className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500"
        >
          Pay
        </button>
      </form>
    </div>
  );
};

export default PaymentPage;
