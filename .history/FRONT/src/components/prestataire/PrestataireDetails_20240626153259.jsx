import React, { useState, useEffect } from 'react';
import CreatePrestataire from './PrestataireType';


const PrestataireDetails = ({ userId }) => {
  const [prestataireDetails, setPrestataireDetails] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const handleSave = async () => {
    await fetchPrestataireDetails(userId);
    setIsModalOpen(false);
  };

  const fetchPrestataireDetails = async (id) => {
    try {
      const response = await fetch(`http://localhost:8000/api/prestataires/${id}`);
      if (!response.ok) {
        throw new Error('Failed to fetch prestataire details');
      }
      const data = await response.json();
      setPrestataireDetails(data);
    } catch (error) {
      setError(error.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (userId) {
      fetchPrestataireDetails(userId);
    }
  }, [userId]);

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="mt-8">
      <h2 className="text-xl font-semibold text-pcs-400 mb-4">Détails du Prestataire</h2>
      {prestataireDetails.map((detail, index) => (
        <div key={index} className="p-4 bg-gray-100 rounded-lg shadow-md mb-4">
          <p className="text-lg font-medium capitalize text-gray-600"><strong>Type de Prestation:</strong> {detail.type}</p>
          <p className="text-lg font-medium text-gray-600"><strong>Tarif/h:</strong> {detail.tarif} €</p>
        </div>
      ))}
      <button
        className="bg-pcs-400 text-white px-4 mt-6 py-2 rounded mb-4"
        onClick={handleOpenModal}
      >
        Ajouter un nouveau type de prestation 
      </button>
      <CreatePrestataire
        isOpen={isModalOpen}
        onClose={handleCloseModal}
        onSave={handleSave}
      />
    </div>
  );
};

export default PrestataireDetails;
