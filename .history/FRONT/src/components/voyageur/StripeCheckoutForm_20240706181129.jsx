// StripeCheckoutForm.jsx
import React, { useState } from 'react';
import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

const StripeCheckoutForm = ({ amount, onSuccess }) => {
  const stripe = useStripe();
  const elements = useElements();
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);

    const { error, paymentMethod } = await stripe.createPaymentMethod({
      type: 'card',
      card: elements.getElement(CardElement),
    });

    if (error) {
      setError(error.message);
      setLoading(false);
      return;
    }

    const response = await fetch('http://localhost:8000/api/create-payment-intent', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ amount, paymentMethodId: paymentMethod.id }),
    });

    const paymentResult = await response.json();

    if (paymentResult.error) {
      setError(paymentResult.error);
      setLoading(false);
      return;
    }

    onSuccess(paymentResult);
    setLoading(false);
  };

  return (
    <form onSubmit={handleSubmit}>
      <CardElement />
      {error && <div className="text-red-500">{error}</div>}
      <button type="submit" disabled={!stripe || loading} className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500">
        {loading ? 'Processing...' : 'Pay'}
      </button>
    </form>
  );
};

export default StripeCheckoutForm;
