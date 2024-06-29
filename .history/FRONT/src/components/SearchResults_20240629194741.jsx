import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import PropertyDetails from './PropertyDetails';

const SearchResults = ({ searchTerm }) => {
  const [properties, setProperties] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedProperty, setSelectedProperty] = useState(null);

  useEffect(() => {
    const fetchProperties = async () => {
      console.log('Fetching properties for search term:', searchTerm);
      try {
        const response = await fetch(`http://localhost:8000/api/search-properties?commune=${searchTerm}`);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Properties fetched:', data);
        setProperties(data);
        setIsLoading(false);
      } catch (err) {
        console.error('Error fetching properties:', err);
        setError('Erreur lors de la récupération des données.');
        setIsLoading(false);
      }
    };

    if (searchTerm) {
      console.log('Search term provided, initiating fetch');
      fetchProperties();
    } else {
      console.log('No search term provided, skipping fetch');
      setProperties([]);
      setIsLoading(false);
    }
  }, [searchTerm]);

  if (isLoading) {
    console.log('Loading...');
    return <div>Chargement...</div>;
  }

  if (error) {
    console.log('Error occurred:', error);
    return <div className="text-red-500">{error}</div>;
  }

  if (properties.length === 0) {
    console.log('No properties found for the search term');
    return <div>Pas de résultat pour cette recherche</div>;
  }

  console.log('Rendering properties:', properties);
  return (
    <div className="mt-10 w-full">
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Résultats de la Recherche</h2>
      <div className="flex flex-wrap">
        {properties.map(property => {
          console.log('PRICE:', property.price);
          return (
            <div key={property.id} className="bg-white shadow-lg rounded-lg overflow-hidden m-4 w-1/4">
              <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover" />
              <div className="p-4">
                <h3 className="text-xl font-semibold mb-2">{property.name}</h3>
                <p className="text-gray-600 mb-4">{property.description}</p>
                <p className="text-gray-600 mb-4">{property.commune}</p>
                <p className="text-gray-600 mb-4">{property.price} €/jour</p>
                <div className="flex justify-between">
                  <button
                    className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md"
                    onClick={() => setSelectedProperty(property)}
                  >
                    Détail +
                  </button>
                  <Link to={`/louer-un-logement?id=${property.id}`} target="_blank" className="bg-pcs-400 text-white py-2 px-4 rounded-md">
                    Louer
                  </Link>
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {selectedProperty && (
        <PropertyDetails property={selectedProperty} onClose={() => setSelectedProperty(null)} />
      )}
    </div>
  );
};

export default SearchResults;
