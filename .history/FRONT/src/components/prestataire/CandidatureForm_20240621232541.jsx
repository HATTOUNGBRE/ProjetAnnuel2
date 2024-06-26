// src/components/CandidatureForm.jsx

import React, { useState, useContext } from 'react';
import AuthContext from '../AuthContext';

const CandidatureForm = ({ reservationId, onCandidatureSubmitted }) => {
  const { userId } = useContext(AuthContext);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const response = await fetch(`http://localhost:8000/api/reservation/${reservationId}/candidature`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ prestataire_id: userId }),
      });

      if (!response.ok) {
        throw new Error('Failed to submit candidature');
      }

      onCandidatureSubmitted();
    } catch (error) {
      setError(error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">
        Postuler
      </button>
      {loading && <p>Loading...</p>}
      {error && <p className="text-red-500">{error}</p>}
    </form>
  );
};

export default CandidatureForm;
