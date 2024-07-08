import React from 'react';

const SearchResults = ({ properties }) => {
  return (
    <div className="mt-10 w-full">
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Résultats de la Recherche</h2>
      {properties.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {properties.map(property => (
            <div key={property.id} className="p-4">
              <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover" />
                <div className="p-4">
                  <h3 className="text-xl font-semibold mb-2">{property.name}</h3>
                  <p className="text-gray-600 mb-4">{property.description}</p>
                  <div className="flex justify-between">
                    <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
                    <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="text-xl text-gray-600">Pas de résultat pour cette recherche</div>
      )}
    </div>
  );
};

export default SearchResults;
