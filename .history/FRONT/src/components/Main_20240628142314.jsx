import React, { useState, useContext, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';
import PropertyRow from './PropertyRow';
import Slider from 'react-slick';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);

  const [searchTerm, setSearchTerm] = useState('');
  const [cities, setCities] = useState([]);
  const [arrivalDate, setArrivalDate] = useState('');
  const [departureDate, setDepartureDate] = useState('');
  const [guests, setGuests] = useState(1);

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

  const handleSearch = (e) => {
    e.preventDefault();
    // Add your search logic here
    console.log('Searching for:', searchTerm, arrivalDate, departureDate, guests);
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
      <div className="mt-10 w-full max-w-4xl bg-white shadow-lg rounded-lg p-4">
        <form onSubmit={handleSearch} className="flex items-center space-x-4">
          <div className="flex-1">
            <label className="block text-gray-700 font-bold mb-2">Destination</label>
            <input
              type="text"
              placeholder="Rechercher une destination"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full px-4 py-2 border rounded-full focus:outline-none"
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
          <div className="flex-1">
            <label className="block text-gray-700 font-bold mb-2">Arrivée</label>
            <input
              type="date"
              value={arrivalDate}
              onChange={(e) => setArrivalDate(e.target.value)}
              className="w-full px-4 py-2 border rounded-full focus:outline-none"
            />
          </div>
          <div className="flex-1">
            <label className="block text-gray-700 font-bold mb-2">Départ</label>
            <input
              type="date"
              value={departureDate}
              onChange={(e) => setDepartureDate(e.target.value)}
              className="w-full px-4 py-2 border rounded-full focus:outline-none"
            />
          </div>
          <div className="flex-1">
            <label className="block text-gray-700 font-bold mb-2">Voyageurs</label>
            <input
              type="number"
              value={guests}
              onChange={(e) => setGuests(e.target.value)}
              min="1"
              className="w-full px-4 py-2 border rounded-full focus:outline-none"
            />
          </div>
          <div className="flex-shrink-0">
            <button type="submit" className="bg-pcs-300 text-white px-4 py-2 rounded-full hover:bg-pcs-400">
              <i className="fa fa-search"></i>
            </button>
          </div>
        </form>
      </div>

      {/* Categories with carousels */}
      <PropertyRow title="Toutes les Propriétés" apiUrl="http://localhost:8000/api/properties" />
      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Appartements Populaires</h2>
        <Slider {...sliderSettings}>
          {[1, 2, 3, 4, 5].map(item => (
            <div key={item} className="p-4">
              <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="http://localhost:8000/uploads/property_photos/default_appart.jpg" alt={`Appartement ${item}`} className="h-48 w-full object-cover" />
                <div className="p-4">
                  <h3 className="text-xl font-semibold mb-2">Appartement {item}</h3>
                  <p className="text-gray-600 mb-4">Description de l'appartement {item}</p>
                  <div className="flex justify-between">
                    <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
                    <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </Slider>
      </div>

      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Nouveaux Appartements</h2>
        <Slider {...sliderSettings}>
          {[1, 2, 3, 4, 5].map(item => (
            <div key={item} className="p-4">
              <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="http://localhost:8000/uploads/property_photos/default_appart.jpg" alt={`Appartement ${item}`} className="h-48 w-full object-cover" />
                <div className="p-4">
                  <h3 className="text-xl font-semibold mb-2">Appartement {item}</h3>
                  <p className="text-gray-600 mb-4">Description de l'appartement {item}</p>
                  <div className="flex justify-between">
                    <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
                    <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </Slider>
      </div>

      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Appartements de Luxe</h2>
        <Slider {...sliderSettings}>
          {[1, 2, 3, 4, 5].map(item => (
            <div key={item} className="p-4">
              <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="http://localhost:8000/uploads/property_photos/default_appart.jpg" alt={`Appartement ${item}`} className="h-48 w-full object-cover" />
                <div className="p-4">
                  <h3 className="text-xl font-semibold mb-2">Appartement {item}</h3>
                  <p className="text-gray-600 mb-4">Description de l'appartement {item}</p>
                  <div className="flex justify-between">
                    <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
                    <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </Slider>
      </div>
    </div>
  );
}

export default Main;
