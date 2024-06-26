import React, { useState } from 'react';

const CandidatureForm = ({ reservationId, onCandidatureSubmitted }) => {
  const [prestataireId, setPrestataireId] = useState('');
  const [start, setStart] = useState('');
  const [end, setEnd] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();

    const response = await fetch(`http://localhost:8000/api/reservation/${reservationId}/candidature`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ prestataire_id: prestataireId, start, end }),
    });

    if (response.ok) {
      onCandidatureSubmitted();
    } else {
      console.error('Failed to submit candidature');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>
          Prestataire ID:
          <input
            type="text"
            value={prestataireId}
            onChange={(e) => setPrestataireId(e.target.value)}
          />
        </label>
      </div>
      <div>
        <label>
          Start Date:
          <input
            type="datetime-local"
            value={start}
            onChange={(e) => setStart(e.target.value)}
          />
        </label>
      </div>
      <div>
        <label>
          End Date:
          <input
            type="datetime-local"
            value={end}
            onChange={(e) => setEnd(e.target.value)}
          />
        </label>
      </div>
      <button type="submit">Submit</button>
    </form>
  );
};

export default CandidatureForm;
