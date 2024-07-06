import React, { useState, useEffect } from 'react';

const SearchForm = ({ handleSearch, searchTerm, setSearchTerm, maxPersons, setMaxPersons }) => {
  const [cities, setCities] = useState([]);

  useEffect(() => {
    if (searchTerm.length >= 2) {
      searchCities(searchTerm);
    } else {
      setCities([]);
    }
  }, [searchTerm]);

  const searchCities = async (term) => {
    console.log('Searching for cities with term:', term);
    try {
      const response = await fetch(`https://geo.api.gouv.fr/communes?nom=${term}&fields=departement&boost=population&limit=5`);
      const data = await response.json();
      console.log('Cities fetched:', data);
      setCities(data);
    } catch (error) {
      console.error('Error fetching cities:', error);
    }
  };

  const handleCitySelect = (cityName) => {
    setSearchTerm(cityName);
    setCities([]);
  };

  return (
    <div className="mt-10 w-full max-w-4xl bg-white shadow-lg rounded-lg p-4">
      <form onSubmit={handleSearch} className="flex items-center space-x-4">
        <div className="relative w-1/3">
          <label className="block text-gray-700 font-bold mb-2">Destination</label>
          <input
            type="text"
            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Rechercher une destination"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
          {cities.length > 0 && (
            <ul className="absolute left-0 mt-2 w-full bg-white border rounded-lg z-10 max-h-48 overflow-y-auto">
              {cities.map((city) => (
                <li 
                  key={city.code} 
                  className="px-4 py-2 border-b last:border-b-0 cursor-pointer hover:bg-gray-200" 
                  onClick={() => handleCitySelect(city.nom)}
                >
                  {city.nom} ({city.departement.nom})
                </li>
              ))}
            </ul>
          )}
        </div>
        <div className="w-1/4">
          <label className="block text-gray-700 font-bold mb-2">Nombre de voyageurs</label>
          <input
            type="number"
            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Nombre de voyageurs"
            value={maxPersons}
            onChange={(e) => setMaxPersons(e.target.value)}
          />
        </div>
        <div>
          <button
            type="submit"
            className="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Rechercher
          </button>
        </div>
      </form>
    </div>
  );
};

export default SearchForm;
