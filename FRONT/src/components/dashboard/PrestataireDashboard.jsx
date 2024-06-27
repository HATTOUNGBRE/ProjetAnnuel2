import React, { useState, useEffect } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import CreatePrestataire from '../prestataire/PrestataireType';
import CandidatureForm from '../prestataire/CandidatureForm';
import PrestataireCalendar from '../prestataire/PrestataireCalendar'; // Import the calendar component

const PrestataireDashboard = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [demandes, setDemandes] = useState([]);
  const [prestations, setPrestations] = useState([]); // State to hold prestations
  const [loading, setLoading] = useState(true);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const handleSave = () => {
    fetchDemandes(); // Fetch demandes again after saving
  };

  const fetchDemandes = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/demande-prestations');
      const data = await response.json();
      setDemandes(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching demandes:', error);
      setLoading(false);
    }
  };

  const fetchPrestations = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/prestations');
      const data = await response.json();
      setPrestations(data);
    } catch (error) {
      console.error('Error fetching prestations:', error);
    }
  };

  const acceptPrestation = async (demandeId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/demande-prestations/${demandeId}/accept`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      if (response.ok) {
        fetchDemandes();
        fetchPrestations(); // Fetch prestations after accepting a demande
      } else {
        console.error('Error accepting demande');
      }
    } catch (error) {
      console.error('Error accepting demande:', error);
    }
  };

  const rejectPrestation = async (demandeId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/demande-prestations/${demandeId}/reject`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      if (response.ok) {
        fetchDemandes();
      } else {
        console.error('Error rejecting demande');
      }
    } catch (error) {
      console.error('Error rejecting demande:', error);
    }
  };

  useEffect(() => {
    fetchDemandes();
    fetchPrestations(); // Fetch prestations on component mount
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

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
  {demandes.filter(demande => demande.statut === 'en attente').length === 0 ? (
    <p className="text-gray-600">Aucune demande pour l'instant</p>
  ) : (
    <div className="grid gap-6 mb-8 md:grid-cols-2">
      {demandes.filter(demande => demande.statut === 'en attente').map(demande => (
        <div key={demande.id} className="p-4 bg-white rounded-lg shadow-md">
          <h6 className="text-lg font-semibold text-gray-800">{demande.titre}</h6>
          <p className="text-sm text-gray-500">
            <strong>Date de début:</strong> {new Date(demande.dateDEffet).toLocaleDateString()} <br />
          </p>
          <p className="text-sm text-yellow-500">{demande.statut}</p>
          <button
            className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition"
            onClick={() => acceptPrestation(demande.id)}
          >
            Accepter
          </button>
          <button
            className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition ml-2"
            onClick={() => rejectPrestation(demande.id)}
          >
            Refuser
          </button>
        </div>
      ))}
    </div>
  )}

  <h4 className="mb-4 mt-10 font-semibold text-gray-800">Demandes de Prestations acceptées</h4>
  {demandes.filter(demande => demande.statut === 'acceptée').length === 0 ? (
    <p className="text-gray-600">Aucune demande acceptée pour l'instant</p>
  ) : (
    <div className="grid gap-6 mb-8 md:grid-cols-2">
      {demandes.filter(demande => demande.statut === 'acceptée').map(demande => (
        <div key={demande.id} className="p-4 bg-white rounded-lg shadow-md">
          <h6 className="text-lg font-semibold text-gray-800">{demande.titre}</h6>
          <p className="text-sm text-gray-500">
            <strong>Date de début:</strong> {new Date(demande.dateDEffet).toLocaleDateString()} <br />
          </p>
          <p className="text-sm text-green-500">{demande.statut}</p>
          <button
            className={`px-4 py-2 rounded-md transition ${new Date(demande.dateDEffet) <= new Date() ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-300 text-gray-600 cursor-not-allowed'}`}
            disabled={new Date(demande.dateDEffet) > new Date()}
            onClick={() => completePrestation(demande.id)}
          >
            Terminé
          </button>
        </div>
      ))}
    </div>
  )}
</div>


          <PrestataireCalendar prestations={prestations} onStatusChange={fetchPrestations} />
        </div>
      </div>
    </div>
  );
};

export default PrestataireDashboard;
