// src/components/prestataire/CandidatureForm.jsx

import React, { useState, useContext } from 'react';
import AuthContext from '../AuthContext';

const CandidatureForm = ({ reservationId, onCandidatureSubmitted }) => {
  const { userId } = useContext(AuthContext);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`http://localhost:8000/api/reservation/${reservationId}/candidature`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId }),
      });

      if (response.ok) {
        alert('Candidature soumise avec succès');
        onCandidatureSubmitted();
      } else {
        alert('Erreur lors de la soumission de la candidature');
      }
    } catch (error) {
      console.error('Erreur:', error);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
        Postuler
      </button>
    </form>
  );
};

export default CandidatureForm;
