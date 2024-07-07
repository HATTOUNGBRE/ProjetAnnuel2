import React from 'react';
import { useLocation } from 'react-router-dom';
import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

const PaymentPage = () => {
  const stripe = useStripe();
  const elements = useElements();
  const location = useLocation();
  const { totalPrice } = location.state;

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
      // Envoyez `paymentMethod.id` au backend pour effectuer le paiement
      try {
        const response = await fetch('http://localhost:8000/api/pay', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            amount: totalPrice * 100, // Montant en centimes
            paymentMethodId: paymentMethod.id,
          }),
        });

        const data = await response.json();
        if (data.success) {
          alert('Payment successful!');
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
