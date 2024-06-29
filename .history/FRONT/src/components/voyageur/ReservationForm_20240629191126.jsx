import React, { useState, useEffect, useContext } from 'react';
import { useParams, useLocation, Link, useNavigate } from 'react-router-dom';
import AuthContext from '../AuthContext';
import ReactModal from 'react-modal';

const ReservationForm = () => {
  const { id } = useParams();
  const { isLoggedIn, userRole, userId, userName, userSurname } = useContext(AuthContext);
  const [property, setProperty] = useState(null);
  const [dateArrivee, setDateArrivee] = useState('');
  const [dateDepart, setDateDepart] = useState('');
  const [guestNb, setGuestNb] = useState(1);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [showConfirmModal, setShowConfirmModal] = useState(false);
  const [totalPrice, setTotalPrice] = useState(0);
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    const fetchProperty = async () => {
      const searchParams = new URLSearchParams(location.search);
      const propertyId = searchParams.get('id');

      if (!propertyId) {
        setError('No property ID provided');
        setIsLoading(false);
        return;
      }

      try {
        const response = await fetch(`http://localhost:8000/api/property-details/${propertyId}`);
        const data = await response.json();
        setProperty(data);
        console.log('Property fetched:', data);
        setIsLoading(false);
      } catch (err) {
        console.error('Error fetching property:', err);
        setError('Error fetching property details');
        setIsLoading(false);
      }
    };

    fetchProperty();
  }, [id, location.search]);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!isLoggedIn || userRole !== 'voyageur') {
      setShowModal(true);
      return;
    }

    if (new Date(dateArrivee) >= new Date(dateDepart)) {
      setError('La date d\'arrivée doit être avant la date de départ');
      return;
    }

    // Calculer le prix total
    const days = (new Date(dateDepart) - new Date(dateArrivee)) / (1000 * 60 * 60 * 24);
    const total = days * property.price;
    setTotalPrice(total);

    // Afficher la modal de confirmation
    setShowConfirmModal(true);
  };

  const confirmReservation = async () => {
    try {
      const response = await fetch(`http://localhost:8000/api/demandes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          dateArrivee,
          dateDepart,
          guestNb,
          property: property.id,
          name: userName, // Assuming you have userName in user context
          surname: userSurname, // Assuming you have userSurname in user context
          voyageurId: userId, // Assuming you have id in user context
          totalPrice
        }),
      });

      if (!response.ok) {
        throw new Error('Failed to create reservation');
      }

      const data = await response.json();
      setSuccess('Reservation successfully created');
      setShowConfirmModal(false);
      console.log('Reservation created:', data);
      setError('');

      // Rediriger vers le dashboard après la réservation
      navigate('/dashboard-voyageur');
    } catch (error) {
      console.error('Error creating reservation:', error);
      setError('Failed to create reservation');
      setSuccess('');
    }
  };

  const handleDateArriveeChange = (e) => {
    setDateArrivee(e.target.value);
    if (new Date(e.target.value) >= new Date(dateDepart)) {
      setError('La date d\'arrivée doit être avant la date de départ');
    } else {
      setError('');
    }
  };

  const handleDateDepartChange = (e) => {
    setDateDepart(e.target.value);
    if (new Date(dateArrivee) >= new Date(e.target.value)) {
      setError('La date d\'arrivée doit être avant la date de départ');
    } else {
      setError('');
    }
  };

  if (isLoading) {
    return <div>Loading...</div>;
  }

  if (!property) {
    return <div>Property not found</div>;
  }

  return (
    <div className="container mx-auto p-10">
      <h2 className="text-2xl font-semibold mb-4">Réserver {property.name}</h2>
      <img src={`http://localhost:8000/uploads/property_photos/${property.image}`} alt={property.name} className="h-48 w-full object-cover mb-4" />
      <p className="text-gray-600 mb-4">{property.description}</p>
      <p className="text-gray-600 mb-4">Commune: {property.commune}</p>
      <p className="text-gray-600 mb-4">Prix: {property.price} €/jour</p>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">
            Date d'arrivée:
            <input type="date" value={dateArrivee} onChange={handleDateArriveeChange} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </label>
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">
            Date de départ:
            <input className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" value={dateDepart} onChange={handleDateDepartChange} required />
          </label>
        </div>
        <div className="mb-4">
          <label className="block text-gray-700 font-bold mb-2">
            Nombre de personnes:
            <input className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="number" value={guestNb} onChange={(e) => setGuestNb(e.target.value)} required min="1" />
          </label>
        </div>
        <button className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500 focus:outline-none focus:ring-2 focus:ring-blue-500" type="submit">Réserver</button>
        {error && <div className="text-red-500">{error}</div>}
        {success && <div className="text-green-500">{success}</div>}
      </form>
      {showModal && (
        <ReactModal isOpen={showModal} onRequestClose={() => setShowModal(false)} className="Modal" overlayClassName="Overlay">
          <h2 className="text-2xl font-semibold mb-4">Veuillez vous connecter</h2>
          <p className="mb-4">Vous devez être connecté en tant que voyageur pour effectuer une réservation.</p>
          <div className="flex justify-around">
            <Link to="/login?voyageur" className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500">Se connecter</Link>
            <Link to="/components/inscription" className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500">S'inscrire</Link>
          </div>
        </ReactModal>
      )}
      {showConfirmModal && (
        <ReactModal isOpen={showConfirmModal} onRequestClose={() => setShowConfirmModal(false)} className="Modal" overlayClassName="Overlay">
          <h2 className="text-2xl font-semibold mb-4">Confirmer la réservation</h2>
          <p className="mb-4">Date d'arrivée: {new Date(dateArrivee).toLocaleDateString()}</p>
          <p className="mb-4">Date de départ: {new Date(dateDepart).toLocaleDateString()}</p>
          <p className="mb-4">Nombre de personnes: {guestNb}</p>
          <p className="mb-4">Prix total: {totalPrice} €</p>
          <div className="flex justify-around">
            <button onClick={confirmReservation} className="bg-green-500 text-white py-2 px-4 rounded-lg">Réserver</button>
            <button onClick={() => setShowConfirmModal(false)} className="bg-gray-500 text-white py-2 px-4 rounded-lg">Modifier</button>
          </div>
        </ReactModal>
      )}
    </div>
  );
};

export default ReservationForm;
