import React, { useContext, useState } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);

  const handleSearch = async () => {
    // Mock API call for demonstration
    const results = await mockApiCall(searchQuery);
    setSearchResults(results);
  };

  const mockApiCall = async (query) => {
    // Mock search results based on query
    return [
      { id: 1, name: 'Result 1', location: query },
      { id: 2, name: 'Result 2', location: query },
    ];
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
        <div className="flex items-center justify-center mb-6">
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full px-4 py-2 border rounded-l-md text-gray-700"
            placeholder="Rechercher une destination (e.g., Paris, France)"
          />
          <button onClick={handleSearch} className="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">
            Rechercher
          </button>
        </div>
        <div className="grid grid-cols-1 gap-6">
          {searchResults.map(result => (
            <div key={result.id} className="bg-white shadow-lg rounded-lg p-4">
              <div className="text-xl font-semibold text-gray-700">{result.name}</div>
              <div className="text-gray-600">{result.location}</div>
              <div className="mt-4 flex justify-between items-center">
                <button className="bg-blue-400 hover:bg-blue-300 text-white py-2 px-4 rounded-md">Détail +</button>
                <button className="bg-blue-600 text-white py-2 px-4 rounded-md">Louer</button>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default Main;
