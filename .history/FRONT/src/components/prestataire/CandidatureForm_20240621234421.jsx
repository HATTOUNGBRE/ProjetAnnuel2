// src/components/CandidatureForm.jsx
import React, { useState, useContext } from 'react';
import AuthContext from '../AuthContext';

const CandidatureForm = ({ reservationId, onCandidatureSubmitted }) => {
  const { userId } = useContext(AuthContext); // Assurez-vous que userId est récupéré du contexte Auth
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await fetch(`http://localhost:8000/api/reservation/${reservationId}/candidature`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ prestataire_id: userId }), // Envoi de l'ID du prestataire
      });

      if (!response.ok) {
        throw new Error('Failed to submit candidature');
      }

      await response.json();
      setLoading(false);
      onCandidatureSubmitted();
    } catch (error) {
      setError(error.message);
      setLoading(false);
    }
  };

  return (
    <div>
      <form onSubmit={handleSubmit}>
        <button type="submit" disabled={loading}>
          {loading ? 'Submitting...' : 'Postuler'}
        </button>
      </form>
      {error && <p>Error: {error}</p>}
    </div>
  );
};

export default CandidatureForm;
