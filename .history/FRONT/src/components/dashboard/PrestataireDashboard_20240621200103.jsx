import React, { useState, useEffect } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import CreatePrestataire from '../prestataire/PrestataireType';
import Calendrier from './Calendar';

const PrestataireDashboard = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [reservations, setReservations] = useState([]);
  const [selectedReservation, setSelectedReservation] = useState(null);
  const [loading, setLoading] = useState(true);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedReservation(null);
  };

  const handleSave = () => {
    // Refresh the list of prestataires or perform any other necessary action
  };

  const fetchReservationDetails = async (reservationId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/reservation/${reservationId}`);
      const data = await response.json();
      setSelectedReservation(data);
      setIsModalOpen(true);
    } catch (error) {
      console.error('Error fetching reservation details:', error);
    }
  };

  useEffect(() => {
    const fetchReservations = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/reservations/unassigned');
        const data = await response.json();
        setReservations(data);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching reservations:', error);
        setLoading(false);
      }
    };

    fetchReservations();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar */}
      <div className="w-64 bg-white shadow-md">
        <div className="p-6">
          <h2 className="text-2xl font-semibold text-gray-800">Dashboard</h2>
          <nav className="mt-10">
            <a className="flex items-center p-2 mt-4 text-gray-600 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
              <FaHome className="w-5 h-5" />
              <span className="mx-4 font-medium">Home</span>
            </a>
            <a className="flex items-center p-2 mt-4 text-gray-600 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
              <FaClipboardList className="w-5 h-5" />
              <span className="mx-4 font-medium">Listings</span>
            </a>
            <a className="flex items-center p-2 mt-4 text-gray-600 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
              <FaUsers className="w-5 h-5" />
              <span className="mx-4 font-medium">Tenants</span>
            </a>
            <a className="flex items-center p-2 mt-4 text-gray-600 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
              <FaCog className="w-5 h-5" />
              <span className="mx-4 font-medium">Settings</span>
            </a>
            <button
              className="bg-pcs-400 text-white px-4 mt-6 py-2 rounded mb-4"
              onClick={handleOpenModal}
            >
              Ajouter Prestataire
            </button>
            <CreatePrestataire
              isOpen={isModalOpen}
              onClose={handleCloseModal}
              onSave={handleSave}
            />
          </nav>
        </div>
      </div>

      {/* Main content */}
      <div className="flex-1 p-6 overflow-auto">
        <h1 className="text-3xl font-semibold text-gray-800">Dashboard Prestataire</h1>

        <div className="mt-6">
          <div className="grid gap-6 mb-8 md:grid-cols-2">
            <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <h4 className="mb-4 font-semibold text-gray-800">Demandes de Réservation sans Prestataire</h4>
              <ul className="divide-y divide-gray-200">
              {reservations.map(reservation => (
              <div key={reservation.id} className="p-6 bg-white rounded-lg shadow-md w-full">
                <h6 className="text-lg font-semibold text-gray-800">{reservation.titre}</h6>
                <p className="text-sm text-gray-500">
                  <strong>Date de début:</strong> {reservation.dateDEffet} <br />
                  <strong>Date de fin:</strong> {reservation.dateDeFin}
                </p>
                <p className={`text-sm ${
                  reservation.statut === 'en attente' ? 'text-yellow-500' :
                  reservation.statut === 'Confirmée' ? 'text-green-500' :
                  'text-red-500'
                }`}>
                  {reservation.statut}
                </p>
                    
                    </div>
                    <div className="space-x-2">
                      <button 
                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition"
                        onClick={() => fetchReservationDetails(reservation.id)}
                      >
                        En savoir +
                      </button>
                    </div>
                  </li>
                
                ))}
              </ul>
            </div>
           
          </div>
          <Calendrier />
        </div>
      </div>

      {selectedReservation && (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 className="text-2xl font-bold mb-4">Détails de la Réservation</h2>
            <div>
              <p className="text-lg font-medium text-gray-600"><strong>Titre:</strong> {selectedReservation.titre}</p>
              <p className="text-lg font-medium text-gray-600"><strong>Date de début:</strong> {selectedReservation.dateDEffet}</p>
              <p className="text-lg font-medium text-gray-600"><strong>Date de fin:</strong> {selectedReservation.dateDeFin}</p>
              <p className="text-lg font-medium text-gray-600"><strong>Statut:</strong> {selectedReservation.statut}</p>
              <div className="mt-4">
                <h3 className="text-xl font-semibold text-pcs-400 mb-2">Détails de la Prestation</h3>
                <p className="text-lg font-medium text-gray-600"><strong>Titre:</strong> {selectedReservation.prestation.titre}</p>
                <p className="text-lg font-medium text-gray-600"><strong>Description:</strong> {selectedReservation.prestation.description}</p>
                <p className="text-lg font-medium text-gray-600"><strong>Type:</strong> {selectedReservation.prestation.type}</p>
              </div>
            </div>
            <div className="flex justify-end space-x-4 mt-6">
              <button className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">Accepter</button>
              <button className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Refuser</button>
              <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition" onClick={handleCloseModal}>Fermer</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default PrestataireDashboard;
