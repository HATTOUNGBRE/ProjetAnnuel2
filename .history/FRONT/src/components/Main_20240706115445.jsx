import React, { useState, useContext, useEffect } from 'react';
import AuthContext from './AuthContext';
import { Link } from 'react-router-dom';
import PropertySliderSection from './PropertySliderSection';
import PropertyRow from './PropertyRow';
import PropertyDetails from './PropertyDetails';
import SearchForm from './SearchForm';
import SearchResults from './SearchResults';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

function Main() {
  const { isLoggedIn, userRole } = useContext(AuthContext);
  const [searchTerm, setSearchTerm] = useState('');
  const [maxPersons, setMaxPersons] = useState('');
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
    console.log('Search initiated with term:', searchTerm, 'and max persons:', maxPersons);
    try {
      const response = await fetch(`http://localhost:8000/api/search-properties?commune=${searchTerm}&maxPersons=${maxPersons}`);
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
        <SearchForm
          handleSearch={handleSearch}
          searchTerm={searchTerm}
          setSearchTerm={setSearchTerm}
          maxPersons={maxPersons}
          setMaxPersons={setMaxPersons}
        />
      ) : null}

      {/* Résultats de la recherche */}
      <SearchResults searchTerm={searchTerm} maxPersons={maxPersons} />

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
