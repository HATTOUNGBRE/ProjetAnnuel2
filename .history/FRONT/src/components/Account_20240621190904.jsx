import React, { useState, useEffect } from 'react';
import CreatePrestataire from './prestataire/PrestataireType';



const Account = ({ userId }) => {
  const [user, setUser] = useState(null);
  const [prestataireDetails, setPrestataireDetails] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleOpenModal = () => {
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const handleSave = () => {
    // Rafraîchir la liste des prestataires ou effectuer toute autre action nécessaire
  };
  if (loading) {
    return <div>Chargement...</div>;
  }

  useEffect(() => {
    if (userId) {
      const fetchUser = async () => {
        try {
          const response = await fetch(`http://localhost:8000/api/user/${userId}`);
          if (!response.ok) {
            throw new Error('Failed to fetch user data');
          }
          const data = await response.json();
          setUser(data);
          
          if (data.category_user === 3) { // Check if user is a Prestataire
            const prestataireResponse = await fetch(`http://localhost:8000/api/prestataires/${userId}`);
            if (!prestataireResponse.ok) {
              throw new Error('Failed to fetch prestataire data');
            }
            const prestataireData = await prestataireResponse.json();
            setPrestataireDetails(prestataireData);
          }
        } catch (error) {
          setError(error.message);
        } finally {
          setLoading(false);
        }
      };

      fetchUser();
    } else {
      setLoading(false);
      setError('User ID is null');
    }
  }, [userId]);

  const getUserType = (categoryUserId) => {
    switch (categoryUserId) {
      case 1:
        return 'Propriétaire';
      case 2:
        return 'Voyageur';
      case 3:
        return 'Prestataire';
      default:
        return 'N/A';
    }
  };

  const formatDate = (date) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString(undefined, options);
  };

  if (loading) {
    return <div className="flex items-center justify-center h-screen">Loading...</div>;
  }

  if (error) {
    return <div className="flex items-center justify-center h-screen text-red-500">{error}</div>;
  }

  if (!user) {
    return <div className="flex items-center justify-center h-screen">No user data</div>;
  }

  return (
    <div className="max-w-4xl mx-auto my-10 p-6 bg-white rounded-lg shadow-md">
      <h1 className="text-2xl font-semibold text-pcs-400 mb-4">Account Details</h1>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="col-span-2">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-pcs-300"><strong>ID:</strong> {user.id}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Name:</strong> {user.name}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Surname:</strong> {user.surname}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Email:</strong> {user.email}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Inscrit.e depuis le:</strong> {formatDate(user.created_at)}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Vous êtes:</strong> {getUserType(user.category_user)}</p>
            </div>
            <div className="p-4 bg-gray-100 rounded-lg shadow-md">
              <p className="text-lg font-medium text-gray-600"><strong>Vérifié:</strong> {user.verified ? 'Oui' : 'Non'}</p>
            </div>
          </div>
        </div>
        <div className="col-span-1 flex justify-center items-center">
          {user.image_profile ? (
            <img
              src={user.image_profile}
              alt="Profile"
              className="w-32 h-32 rounded-full"
            />
          ) : (
            <div className="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
              <span className="text-gray-500">No Image</span>
            </div>
          )}
        </div>
      </div>
      {user.category_user === 3 && prestataireDetails && (
        <div className="mt-8">
          <h2 className="text-xl font-semibold text-pcs-400 mb-4">Détails du Prestataire</h2>
          <div className="p-4 bg-gray-100 rounded-lg shadow-md">
            <p className="text-lg font-medium capitalize text-gray-600"><strong>Type de Prestation:</strong> {prestataireDetails.type}</p>
            <p className="text-lg font-medium text-gray-600"><strong>Tarif/h:</strong> {prestataireDetails.tarif} €</p>
          </div>
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
      )}
    </div>
  );
};

export default Account;
