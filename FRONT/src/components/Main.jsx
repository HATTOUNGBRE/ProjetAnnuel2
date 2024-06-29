import React, { useState, useContext, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';
import PropertySliderSection from './PropertySliderSection';
import PropertyRow from './PropertyRow';
import PropertyDetails from './PropertyDetails';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);
  const [searchTerm, setSearchTerm] = useState('');
  const [cities, setCities] = useState([]);
  const [properties, setProperties] = useState([]);
  const [allProperties, setAllProperties] = useState([]);
  const [selectedProperty, setSelectedProperty] = useState(null);
  const [showDetails, setShowDetails] = useState(false);
  const [showReservationForm, setShowReservationForm] = useState(false);

  useEffect(() => {
    if (searchTerm.length >= 2) {
      searchCities(searchTerm);
    } else {
      setCities([]);
    }
  }, [searchTerm]);

  useEffect(() => {
    fetchAllProperties();
  }, []);

  const fetchAllProperties = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/properties');
      const data = await response.json();
      setAllProperties(data);
      console.log('All properties fetched:', data);
    } catch (error) {
      console.error('Error fetching all properties:', error);
    }
  };

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

  const handleSearch = async (e) => {
    e.preventDefault();
    console.log('Search initiated with term:', searchTerm);
    try {
      const response = await fetch(`http://localhost:8000/api/search-properties?commune=${searchTerm}`);
      const data = await response.json();
      console.log('Properties fetched:', data);
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

  const handleShowDetails = (property) => {
    setSelectedProperty(property);
    setShowDetails(true);
  };

  const handleShowReservationForm = (property) => {
    setSelectedProperty(property);
    setShowReservationForm(true);
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
            <button
              type="submit"
              className="bg-pcs-300 mt-8 text-white py-2 px-4 rounded-lg hover:bg-pcs-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Rechercher
            </button>
          </form>
        </div>
      ) : null}

      {/* Résultats de la recherche */}
      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Résultats de la Recherche</h2>
        {properties.length === 0 ? (
          <div>Pas de résultat pour cette recherche</div>
        ) : (
          <div className="flex flex-wrap">
            {properties.map(property => (
              <div key={property.id} className="p-4 w-full md:w-1/3">
                <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                  <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover" />
                  <div className="p-4">
                    <h3 className="text-xl font-semibold mb-2">{property.name}</h3>
                    <p className="text-gray-600 mb-4">{property.description}</p>
                    <p className="text-gray-600 mb-4">{property.commune}</p>
                    <div className="flex justify-between">
                      <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md" onClick={() => setSelectedProperty(property)}>Détail +</button>
                      <Link to={`/louer-un-logement?id=${property.id}`} className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</Link>
                    </div>               
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Section pour toutes les propriétés */}
      <div className="mt-10 w-full">
        <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Toutes les Propriétés</h2>
        <PropertyRow properties={allProperties} sliderSettings={sliderSettings} />
      </div>

      {/* Sections de propriétés fictives */}
      <PropertySliderSection title="Appartements Populaires" sliderSettings={sliderSettings} />
      <PropertySliderSection title="Nouveaux Appartements" sliderSettings={sliderSettings} />
      <PropertySliderSection title="Appartements de Luxe" sliderSettings={sliderSettings} />

      {/* Modals for property details and reservation form */}
      {showDetails && selectedProperty && (
        <PropertyDetails property={selectedProperty} onClose={() => setShowDetails(false)} />
      )}

      {showReservationForm && selectedProperty && (
        <ReservationForm property={selectedProperty} onClose={() => setShowReservationForm(false)} />
      )}
    </div>
  );
}

export default Main;
