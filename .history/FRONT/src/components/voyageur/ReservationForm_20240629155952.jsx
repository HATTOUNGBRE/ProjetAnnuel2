import React, { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom';

const ReservationForm = () => {
  const [property, setProperty] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
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
        const response = await fetch(`http://localhost:8000/api/properties/${propertyId}`);
        const data = await response.json();
        setProperty(data);
        setIsLoading(false);
      } catch (err) {
        console.error('Error fetching property:', err);
        setError('Error fetching property details');
        setIsLoading(false);
      }
    };

    fetchProperty();
  }, [location.search]);

  if (isLoading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div className="text-red-500">{error}</div>;
  }

  if (!property) {
    return <div>No property found</div>;
  }

  return (
    <div className="container mx-auto p-4">
      <h2 className="text-2xl font-semibold mb-4">Réserver {property.name}</h2>
      <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover mb-4" />
      <p className="text-gray-600 mb-4">{property.description}</p>
      <p className="text-gray-600 mb-4">Commune: {property.commune}</p>
      <p className="text-gray-600 mb-4">Prix: {property.price} €/jour</p>

      {/* Formulaire de réservation */}
      <form>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">Date d'arrivée</label>
          <input type="date" className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">Date de départ</label>
          <input type="date" className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">Nombre de voyageurs</label>
          <input type="number" min="1" className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button type="submit" className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500 focus:outline-none focus:ring-2 focus:ring-blue-500">Réserver</button>
      </form>
    </div>
  );
};

export default ReservationForm;
