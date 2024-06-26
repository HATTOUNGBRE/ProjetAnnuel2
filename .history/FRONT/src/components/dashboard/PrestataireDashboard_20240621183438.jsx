import React, { useState, useEffect } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import CreatePrestataire from '../prestataire/PrestataireType';
import Calendrier from './Calendar';

const PrestataireDashboard = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [reservations, setReservations] = useState([]);
  const [loading, setLoading] = useState(true);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const handleSave = () => {
    // Rafraîchir la liste des prestataires ou effectuer toute autre action nécessaire
  };

  useEffect(() => {
    const fetchReservations = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/reservations/unassigned');
        const data = await response.json();
        setReservations(data);
        setLoading(false);
      } catch (error) {
        console.error('Erreur:', error);
        setLoading(false);
      }
    };

    fetchReservations();
  }, []);

  if (loading) {
    return <div>Chargement...</div>;
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
              className="bg-green-500 text-white px-4 py-2 rounded mb-4"
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
          <div className="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <h4 className="mb-4 font-semibold text-gray-800">Total Properties</h4>
              <p className="text-gray-600">12</p>
            </div>
            <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <h4 className="mb-4 font-semibold text-gray-800">Occupied</h4>
              <p className="text-gray-600">8</p>
            </div>
            <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <h4 className="mb-4 font-semibold text-gray-800">Vacant</h4>
              <p className="text-gray-600">4</p>
            </div>
          </div>
        </div>

        {/* Charts section */}
        <div className="mt-6">
          <div className="grid gap-6 mb-8 md:grid-cols-2">
            <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <h4 className="mb-4 font-semibold text-gray-800">Demandes de Réservation sans Prestataire</h4>
              <ul className="divide-y divide-gray-200">
                {reservations.map(reservation => (
                  <li key={reservation.id} className="py-4 flex justify-between items-center">
                    <div>
                      <h6 className="text-sm font-semibold text-gray-800">{reservation.titre}</h3>
                      <p className="text-sm text-gray-500">
                        <strong>Date de début:</strong> {reservation.dateDEffet} <br />
                        <strong>Date de fin:</strong> {reservation.dateDeFin}
                      </p>
                      <p className={`text-sm ${
                        reservation.statut === 'En attente' ? 'text-yellow-500' :
                        reservation.statut === 'Confirmée' ? 'text-green-500' :
                        'text-red-500'
                      }`}>
                        {reservation.statut}
                      </p>
                    </div>
                    <div className="space-x-2">
                      <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Accepter</button>
                      <button className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Refuser</button>
                    </div>
                  </li>
                ))}
              </ul>
            </div>
          </div>
          <Calendrier />
        </div>
      </div>
    </div>
  );
};

export default PrestataireDashboard;
