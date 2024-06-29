import React from 'react';
import { useState } from 'react';

const PropertySearch = ({ onSearch }) => {
  const [commune, setCommune] = useState('');
  const [maxPersons, setMaxPersons] = useState(1);

  const handleSubmit = (e) => {
    e.preventDefault();
    onSearch({ commune, maxPersons });
  };

  return (
    <form onSubmit={handleSubmit} className="flex items-center space-x-4">
      <div className="w-1/2">
        <label className="block text-gray-700 font-bold mb-2">Commune</label>
        <input
          type="text"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          value={commune}
          onChange={(e) => setCommune(e.target.value)}
        />
      </div>
      <div className="w-1/4">
        <label className="block text-gray-700 font-bold mb-2">Max Persons</label>
        <input
          type="number"
          min="1"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          value={maxPersons}
          onChange={(e) => setMaxPersons(e.target.value)}
        />
      </div>
      <button
        type="submit"
        className="bg-pcs-300 mt-8 text-white py-2 px-4 rounded-lg hover:bg-pcs-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Search
      </button>
    </form>
  );
};

export default PropertySearch;
