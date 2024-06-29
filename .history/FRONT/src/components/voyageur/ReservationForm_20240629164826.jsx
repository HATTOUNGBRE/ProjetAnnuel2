import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useLocation } from 'react-router-dom';


const ReservationForm = () => {
  const { id } = useParams();
  const [property, setProperty] = useState(null);
  const [dateArrivee, setDateArrivee] = useState('');
  const [dateDepart, setDateDepart] = useState('');
  const [guestNb, setGuestNb] = useState(1);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
    const [isLoading, setIsLoading] = useState(true);
  const location = useLocation();

  useEffect(() => {
    const fetchProperty = async () => {
      const searchParams = new URLSearchParams(location.search);
      const propertyId = searchParams.get('id');

      if (!propertyId) {
        setError('No property ID provided');
        setIsLoading(false);
        return;
      }

      try {
        const response = await fetch(`http://localhost:8000/api/property-details/${propertyId}`);
        const data = await response.json();
        setProperty(data);
        console.log('Property fetched:', data);
        setIsLoading(false);
      } catch (err) {
        console.error('Error fetching property:', err);
        setError('Error fetching property details');
        setIsLoading(false);
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
      <div className="mb-4">
        <label className="block text-gray-700 font-bold mb-2">
            Date d'arrivée:
            <input type="date" value={dateArrivee} onChange={(e) => setDateArrivee(e.target.value)} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

          </label>
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">
            Date de départ:
            <input  className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" value={dateDepart} onChange={(e) => setDateDepart(e.target.value)} required />
          </label>
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">
            Nombre de personnes:
            <input  className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="number" value={guestNb} onChange={(e) => setGuestNb(e.target.value)} required min="1" />
          </label>
        </div>
        <button  className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500 focus:outline-none focus:ring-2 focus:ring-blue-500" type="submit">Réserver</button>
        {error && <div className="text-red-500">{error}</div>}
        {success && <div className="text-green-500">{success}</div>}
      </form>
    </div>
  );
};

export default ReservationForm;
