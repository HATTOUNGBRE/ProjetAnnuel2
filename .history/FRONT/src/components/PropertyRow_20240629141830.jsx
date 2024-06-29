import React from 'react';

const PropertyRow = ({ property }) => {
  return (
    <div className="bg-white shadow-lg rounded-lg overflow-hidden">
      <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover" />
      <div className="p-4">
        <h3 className="text-xl font-semibold mb-2">{property.name}</h3>
        <p className="text-gray-600 mb-4">{property.description}</p>
        <p className="text-gray-600 mb-4">{property.commune}</p>
        <div className="flex justify-between">
          <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
          <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
        </div>
      </div>
    </div>
  );
};

export default PropertyRow;
