// src/components/PropertySearch.jsx

import React, { useState } from 'react';

const PropertySearch = ({ onSearch }) => {
  const [commune, setCommune] = useState('');
  const [maxPersons, setMaxPersons] = useState('');

  const handleSearch = () => {
    onSearch({ commune, maxPersons });
  };

  return (
    <div className="flex items-center space-x-4">
      <input
        type="text"
        placeholder="Rechercher une destination"
        value={commune}
        onChange={(e) => setCommune(e.target.value)}
        className="border rounded px-4 py-2"
      />
      <input
        type="number"
        placeholder="Nombre de personnes"
        value={maxPersons}
        onChange={(e) => setMaxPersons(e.target.value)}
        className="border rounded px-4 py-2"
      />
      <button onClick={handleSearch} className="bg-pcs-300 text-white px-4 py-2 rounded">
        Rechercher
      </button>
    </div>
  );
};

export default PropertySearch;
