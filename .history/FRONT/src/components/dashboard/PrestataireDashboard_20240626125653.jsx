import React, { useState, useEffect } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import CreatePrestataire from '../prestataire/PrestataireType';
import PrestataireCalendar from '../prestataire/PrestataireCalendar';
import CandidatureForm from '../prestataire/CandidatureForm';

const PrestataireDashboard = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [prestations, setPrestations] = useState([]);
  const [selectedPrestation, setSelectedPrestation] = useState(null);
  const [loading, setLoading] = useState(true);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedPrestation(null);
  };

  const handleSave = () => {
    // Refresh the list of prestataires or perform any other necessary action
  };

  const fetchPrestationDetails = async (prestationId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/prestation/${prestationId}`);
      const data = await response.json();
      setSelectedPrestation(data.prestation);
      setIsModalOpen(true);
    } catch (error) {
      console.error('Error fetching prestation details:', error);
    }
  };

  const acceptPrestation = async (prestationId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/prestations/${prestationId}/accept`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      if (response.ok) {
        // Refresh the list of prestations after accepting
        fetchPrestations();
      } else {
        console.error('Error accepting prestation');
      }
    } catch (error) {
      console.error('Error accepting prestation:', error);
    }
  };

 

  if (loading) {
    return <div>Loading...</div>;
  }

  const pendingPrestations = prestations.filter(prestation => prestation.statut === 'en attente');

  return (
    <div>
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
            <h4 className="mb-4 font-semibold text-gray-800">Demandes de Prestations en attente</h4>
            <div className="grid gap-6 mb-8 md:grid-cols-2">
              {pendingPrestations.map(prestation => (
                <div key={prestation.id} className="p-4 bg-white rounded-lg shadow-md">
                  <h6 className="text-lg font-semibold text-gray-800">{prestation.titre}</h6>
                  <p className="text-sm text-gray-500">
                    <strong>Date de début:</strong> {prestation.dateDEffet} <br />
                    <strong>Date de fin:</strong> {prestation.dateDeFin}
                  </p>
                  <p className={`text-sm ${
                    prestation.statut === 'en attente' ? 'text-yellow-500' :
                    prestation.statut === 'Confirmée' ? 'text-green-500' :
                    'text-red-500'
                  }`}>
                    {prestation.statut}
                  </p>
                  <button
                    className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition"
                    onClick={() => acceptPrestation(prestation.id)}
                  >
                    Accepter
                  </button>
                </div>
              ))}
            </div>
          </div>
          <PrestataireCalendar />
        </div>
      </div>

      {selectedPrestation && (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 className="text-2xl font-bold mb-4">Détails de la Prestation</h2>
            <div>
              <p className="text-lg font-medium text-gray-600"><strong>Titre:</strong> {selectedPrestation.titre}</p>
              <p className="text-lg font-medium text-gray-600"><strong>Description:</strong> {selectedPrestation.description}</p>
              <p className="text-lg font-medium text-gray-600"><strong>Type:</strong> {selectedPrestation.type}</p>
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
