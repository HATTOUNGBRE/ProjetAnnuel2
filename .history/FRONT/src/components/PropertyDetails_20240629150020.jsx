import React from 'react';

const PropertyDetails = ({ property, onClose }) => {
  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div className="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 className="text-2xl font-semibold mb-4">{property.name}</h2>
        <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover mb-4" />
        <p className="text-gray-600 mb-4">{property.description}</p>
        <p className="text-gray-600 mb-4">Commune: {property.commune}</p>
        <p className="text-gray-600 mb-4">Prix: {property.price} €/jour</p>
        <button className="bg-pcs-300 text-white py-2 px-4 rounded-lg" onClick={onClose}>Fermer</button>
      </div>
    </div>
  );
};

export default PropertyDetails;
