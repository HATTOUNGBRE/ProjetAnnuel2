import React, { useContext, useState, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';

const mockCityApiCall = async (query) => {
  // Mock API call for city names starting with the query
  const cities = [
    'Paris', 'Bordeaux', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Lille'
  ];
  return cities.filter(city => city.toLowerCase().startsWith(query.toLowerCase()));
};

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);
  const [searchQuery, setSearchQuery] = useState('');
  const [suggestions, setSuggestions] = useState([]);
  const [searchResults, setSearchResults] = useState([]);

  useEffect(() => {
    const fetchSuggestions = async () => {
      if (searchQuery.length >= 2) {
        const results = await mockCityApiCall(searchQuery);
        setSuggestions(results);
      } else {
        setSuggestions([]);
      }
    };

    fetchSuggestions();
  }, [searchQuery]);

  const handleSearch = async () => {
    setSearchResults(suggestions);
  };

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col items-center justify-center">
      <div className="max-w-3xl w-full p-8 bg-white shadow-md rounded-lg text-center">
        <h1 className="text-3xl font-semibold text-gray-700 mb-6">Accueil</h1>
        {isLoggedIn ? (
          <p className="text-xl text-gray-600">
            Vous êtes connecté.e en tant que <span className="font-bold">{userRole}</span>
          </p>
        ) : (
          <div>
            <p className="text-xl text-gray-600">Bienvenue sur notre site.</p>
            <p className="text-xl text-gray-600">Veuillez vous identifier pour accéder à votre compte.</p>
            <div className='flex justify-center mt-4 space-x-4'>
              <Link to="/components/catUser" className='w-full px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-600 text-center'>
                Se connecter
              </Link>
              <Link to="/components/inscription" className='w-full px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-600 text-center'>
                S'inscrire
              </Link>
            </div>
          </div>
        )}
      </div>

      <div className="mt-10 w-full max-w-3xl">
        <div className="relative mb-6">
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full px-4 py-2 border rounded-md text-gray-700"
            placeholder="Rechercher une destination (e.g., Paris, France)"
          />
          {suggestions.length > 0 && (
            <ul className="absolute left-0 right-0 bg-white border rounded-md mt-1 max-h-60 overflow-auto z-10">
              {suggestions.map((suggestion, index) => (
                <li
                  key={index}
                  onClick={() => setSearchQuery(suggestion)}
                  className="px-4 py-2 cursor-pointer hover:bg-gray-200"
                >
                  {suggestion}
                </li>
              ))}
            </ul>
          )}
          <button onClick={handleSearch} className="px-4 py-2 mt-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            Rechercher
          </button>
        </div>

        <div className="grid grid-cols-1 gap-6">
          {searchResults.map((result, index) => (
            <div key={index} className="bg-white shadow-lg rounded-lg p-4">
              <div className="text-xl font-semibold text-gray-700">{result}</div>
              <div className="mt-4 flex justify-between items-center">
                <button className="bg-blue-400 hover:bg-blue-300 text-white py-2 px-4 rounded-md">Détail +</button>
                <button className="bg-blue-600 text-white py-2 px-4 rounded-md">Louer</button>
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className="mt-10 grid grid-rows-3 gap-6 mb-8">
        {[1, 2, 3].map(row => (
          <div key={row} className="flex justify-center space-x-6">
            {[1, 2, 3].map(item => (
              <div key={item} className="bg-white shadow-lg rounded-lg p-4 w-64">
                <div className="h-40 bg-gray-200 rounded-t-lg"></div>
                <div className="mt-4 flex justify-between items-center">
                  <button className="bg-blue-400 hover:bg-blue-300 text-white py-2 px-4 rounded-md">Détail +</button>
                  <button className="bg-blue-600 text-white py-2 px-4 rounded-md">Louer</button>
                </div>
              </div>
            ))}
          </div>
        ))}
      </div>
    </div>
  );
}

export default Main;
