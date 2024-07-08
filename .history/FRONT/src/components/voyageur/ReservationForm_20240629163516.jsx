import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';

const ReservationForm = () => {
  const { id } = useParams();
  const [property, setProperty] = useState(null);
  const [dateArrivee, setDateArrivee] = useState('');
  const [dateDepart, setDateDepart] = useState('');
  const [guestNb, setGuestNb] = useState(1);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  useEffect(() => {
    const fetchProperty = async () => {
      try {
        const response = await fetch(`http://localhost:8000/api/property-details/${propertyId}`);
        const data = await response.json();
        setProperty(data);
      } catch (error) {
        console.error('Error fetching property:', error);
      }
    };

    fetchProperty();
  }, [id]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`http://localhost:8000/api/reservations`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          dateArrivee,
          dateDepart,
          guestNb,
          property: id,
        }),
      });

      if (!response.ok) {
        throw new Error('Failed to create reservation');
      }

      const data = await response.json();
      setSuccess('Reservation successfully created');
      setError('');
      console.log('Reservation created:', data);
    } catch (error) {
      console.error('Error creating reservation:', error);
      setError('Failed to create reservation');
      setSuccess('');
    }
  };

  if (!property) {
    return <div>Loading...</div>;
  }

  return (
    <div>
      <h1>Réservation pour {property.name}</h1>
      <form onSubmit={handleSubmit}>
        <div>
          <label>
            Date d'arrivée:
            <input type="date" value={dateArrivee} onChange={(e) => setDateArrivee(e.target.value)} required />
          </label>
        </div>
        <div>
          <label>
            Date de départ:
            <input type="date" value={dateDepart} onChange={(e) => setDateDepart(e.target.value)} required />
          </label>
        </div>
        <div>
          <label>
            Nombre de personnes:
            <input type="number" value={guestNb} onChange={(e) => setGuestNb(e.target.value)} required min="1" />
          </label>
        </div>
        <button type="submit">Réserver</button>
        {error && <div className="text-red-500">{error}</div>}
        {success && <div className="text-green-500">{success}</div>}
      </form>
    </div>
  );
};

export default ReservationForm;
