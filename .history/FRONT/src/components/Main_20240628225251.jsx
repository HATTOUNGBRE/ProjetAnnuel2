import React, { useState, useContext, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';
import PropertySliderSection from './PropertySliderSection';
import PropertyRowSlider from './PropertyRowSlider';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);

  const [searchTerm, setSearchTerm] = useState('');
  const [cities, setCities] = useState([]);
  const [arrivalDate, setArrivalDate] = useState('');
  const [departureDate, setDepartureDate] = useState('');
  const [guests, setGuests] = useState(1);
  const [properties, setProperties] = useState([]);

  useEffect(() => {
    if (searchTerm.length >= 2) {
      searchCities(searchTerm);
    } else {
      setCities([]);
    }
  }, [searchTerm]);

  useEffect(() => {
    // Fetch properties from the backend on component mount
    const fetchProperties = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/properties');
        const data = await response.json();
        setProperties(data);
      } catch (error) {
        console.error('Error fetching properties:', error);
      }
    };
    fetchProperties();
  }, []);

  const searchCities = async (term) => {
    try {
      const response = await fetch(`https://geo.api.gouv.fr/communes?nom=${term}&fields=departement&boost=population&limit=5`);
      const data = await response.json();
      setCities(data);
    } catch (error) {
      console.error('Error fetching cities:', error);
    }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(
        `http://localhost:8000/api/search-properties?commune=${searchTerm}&maxPersons=${guests}`
      );
      const data = await response.json();
      setProperties(data);
      setCities([]);  // Effacer les suggestions après la sélection
    } catch (error) {
      console.error('Error fetching properties:', error);
    }
  };

  const sliderSettings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
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

      {/* Barre de recherche */}
      {!isLoggedIn || userRole === 'voyageur' ? (
        <div className="mt-10 w-full max-w-4xl bg-white shadow-lg rounded-lg p-4">
          <form onSubmit={handleSearch} className="flex items-center space-x-4">
            <div className="relative w-1/4">
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
                    <li key={city.code} className="px-4 py-2 border-b last:border-b-0 cursor-pointer hover:bg-gray-200" onClick={() => setSearchTerm(city.nom)}>
                      {city.nom} ({city.departement.nom})
                    </li>
                  ))}
                </ul>
              )}
            </div>
            <div className="w-1/4">
              <label className="block text-gray-700 font-bold mb-2">Arrivée</label>
              <input
                type="date"
                className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value={arrivalDate}
                onChange={(e) => setArrivalDate(e.target.value)}
              />
            </div>
            <div className="w-1/4">
              <label className="block text-gray-700 font-bold mb-2">Départ</label>
              <input
                type="date"
                className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value={departureDate}
                onChange={(e) => setDepartureDate(e.target.value)}
              />
            </div>
            <div className="w-1/4">
              <label className="block text-gray-700 font-bold mb-2">Voyageurs</label>
              <input
                type="number"
                min="1"
                className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value={guests}
                onChange={(e) => setGuests(e.target.value)}
              />
            </div>
            <button
              type="submit"
              className="bg-pcs-300 mt-8 text-white py-2 px-4 rounded-lg hover:bg-pcs-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Rechercher
            </button>
          </form>
        </div>
      ) : null}

      {/* Propriétés depuis la base de données */}
      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Toutes les Propriétés</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {properties.map((property) => (
            <PropertyRow key={property.id} property={property} />
          ))}
        </div>
      </div>

      {/* Sections de propriétés fictives */}
      <PropertySliderSection title="Appartements Populaires" sliderSettings={sliderSettings} />
      <PropertySliderSection title="Nouveaux Appartements" sliderSettings={sliderSettings} />
      <PropertySliderSection title="Appartements de Luxe" sliderSettings={sliderSettings} />
    </div>
  );
}

export default Main;
