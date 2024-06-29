import React, { useEffect, useState } from 'react';
import PropertyRow from './PropertyRow';

const SearchResults = ({ searchTerm }) => {
  const [properties, setProperties] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchProperties = async () => {
      console.log('Fetching properties for search term:', searchTerm);
      try {
        const response = await fetch(`http://localhost:8000/api/search-properties?commune=${searchTerm}`);
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
      fetchProperties();
    } else {
      console.log('No search term provided, skipping fetch');
      setProperties([]);
      setIsLoading(false);
    }
  }, [searchTerm]);

  if (isLoading) {
    return <div>Chargement...</div>;
  }

  if (error) {
    return <div className="text-red-500">{error}</div>;
  }

  if (properties.length === 0) {
    return <div>Pas de résultat pour cette recherche</div>;
  }

  return (
    <div>
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Résultats de la Recherche</h2>
      <div className="flex flex-col">
        {properties.map(property => (
          <PropertyRow key={property.id} property={property} />
        ))}
      </div>
    </div>
  );
};

export default SearchResults;
