import React, { useState, useEffect } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import CreatePrestataire from '../prestataire/PrestataireType';
import CandidatureForm from '../prestataire/CandidatureForm';
import PrestataireCalendar from '../prestataire/PrestataireCalendar';
const PrestataireDashboard = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [demandes, setDemandes] = useState([]);
  const [prestations, setPrestations] = useState([]);
  const [loading, setLoading] = useState(true);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const handleSave = () => {
    fetchPrestations();
  };

  const fetchDemandes = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/demande-prestations');
      const data = await response.json();
      setDemandes(data);
    } catch (error) {
      console.error('Error fetching demandes:', error);
    }
  };

  const fetchPrestations = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/prestations');
      const data = await response.json();
      setPrestations(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching prestations:', error);
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDemandes();
    fetchPrestations();
  }, []);

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
        fetchPrestations();
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
        fetchPrestations();
      } else {
        console.error('Error rejecting demande');
      }
    } catch (error) {
      console.error('Error rejecting demande:', error);
    }
  };

  const handleStatusChange = async (prestationId, status) => {
    try {
      const response = await fetch(`http://localhost:8000/api/prestations/${prestationId}/status`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status }),
      });
      if (response.ok) {
        fetchPrestations();
      } else {
        console.error('Error updating prestation status');
      }
    } catch (error) {
      console.error('Error updating prestation status:', error);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div>
      <div className="flex h-screen bg-gray-100">
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

        <div className="flex-1 p-6 overflow-auto">
          <h1 className="text-3xl font-semibold text-gray-800">Dashboard Prestataire</h1>

          <div className="mt-6">
            <h4 className="mb-4 font-semibold text-gray-800">Demandes de Prestations en attente</h4>
            <div className="grid gap-6 mb-8 md:grid-cols-2">
              {demandes.map(demande => (
                <div key={demande.id} className="p-4 bg-white rounded-lg shadow-md">
                  <h6 className="text-lg font-semibold text-gray-800">{demande.titre}</h6>
                  
                  <p className="text-sm text-gray-500">
                    <strong>Date de début:</strong> {demande.dateDEffet} <br />
                  </p>
                  <p className={`text-sm ${
                    demande.statut === 'en attente' ? 'text-yellow-500' :
                    demande.statut === 'Confirmée' ? 'text-green-500' :
                    'text-red-500'
                  }`}>
                    {demande.statut}
                  </p>
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
          </div>
          <PrestataireCalendar prestations={prestations} onStatusChange={handleStatusChange} />
        </div>
      </div>
    </div>
  );
};

export default PrestataireDashboard;
