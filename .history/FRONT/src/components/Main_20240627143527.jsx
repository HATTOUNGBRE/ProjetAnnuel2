// src/components/Main.jsx
import React, { useState, useContext, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';
import PropertyRow from './PropertyRow';

const Main = () => {
  const { isLoggedIn, userRole } = useContext(AuthContext);

  const [searchTerm, setSearchTerm] = useState('');
  const [cities, setCities] = useState([]);

  useEffect(() => {
    if (searchTerm.length >= 2) {
      searchCities(searchTerm);
    } else {
      setCities([]);
    }
  }, [searchTerm]);

  const searchCities = async (term) => {
    try {
      const response = await fetch(`https://geo.api.gouv.fr/communes?nom=${term}&fields=departement&boost=population&limit=5`);
      const data = await response.json();
      setCities(data);
    } catch (error) {
      console.error('Error fetching cities:', error);
    }
  };

  return (
    <div className="min-h-screen bg-pcs-100 flex flex-col items-center justify-center">
      <div className="max-w-3xl w-full p-8 bg-white shadow-md rounded-lg text-center">
        <h1 className="text-3xl font-semibold text-pcs-400 mb-6">Accueil</h1>
        {isLoggedIn ? (
          <p className="text-xl text-pcs-300">
            Vous êtes connecté.e en tant que <span className="font-bold">{userRole}</span>
          </p>
        ) : (
          <div>
            <p className="text-xl text-gray-600">Bienvenue sur notre site.</p>
            <p className="text-xl text-gray-600">Veuillez vous identifier pour accéder à votre compte.</p>
            <div className='flex justify-center mt-4 space-x-10'>
              <button className='w-full px-4 mb-4 py-2 text-sm font-medium text-white bg-pcs-300 border border-transparent rounded-md shadow-sm hover:bg-pcs-400'>
                <Link to="/components/catUser">Se connecter</Link>
              </button>
              <button className='w-full px-4 mb-4 py-2 text-sm font-medium text-white bg-pcs-300 border border-transparent rounded-md shadow-sm hover:bg-pcs-400'>
                <Link to="/components/inscription">S'inscrire</Link>
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Barre de recherche de villes */}
      <div className="mt-10 w-full max-w-md">
        <input
          type="text"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Rechercher une ville"
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
        {cities.length > 0 && (
          <ul className="mt-2 bg-white border rounded-lg">
            {cities.map((city) => (
              <li key={city.code} className="px-4 py-2 border-b last:border-b-0">
                {city.nom} ({city.departement.nom})
              </li>
            ))}
          </ul>
        )}
      </div>

      {/* Categories with carousels */}
      <PropertyRow title="Appartements Populaires" apiUrl="http://localhost:8000/api/properties/popular" />
      <PropertyRow title="Nouveaux Appartements" apiUrl="http://localhost:8000/api/properties/new" />
      <PropertyRow title="Appartements de Luxe" apiUrl="http://localhost:8000/api/properties/luxury" />
    </div>
  );
};

export default Main;
