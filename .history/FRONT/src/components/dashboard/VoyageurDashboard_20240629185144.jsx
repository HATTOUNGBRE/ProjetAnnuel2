import React, { useState, useEffect, useContext } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import AuthContext from '../AuthContext';
import ReactModal from 'react-modal';

const VoyageurDashboard = () => {
    const { userId } = useContext(AuthContext);
    const [demandes, setDemandes] = useState([]);
    const [historique, setHistorique] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [demandeToCancel, setDemandeToCancel] = useState(null);

    useEffect(() => {
        const fetchDemandes = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/demandes/voyageur/${userId}`);
                const data = await response.json();
                setDemandes(data);
            } catch (error) {
                console.error('Error fetching demandes:', error);
            }
        };

        const fetchHistorique = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/historique/voyageur/${userId}`);
                const data = await response.json();
                setHistorique(data);
            } catch (error) {
                console.error('Error fetching historique:', error);
            }
        };

        fetchDemandes();
        fetchHistorique();
    }, [userId]);

    const handleCancel = (demande) => {
        setDemandeToCancel(demande);
        setShowModal(true);
    };

    const confirmCancel = async () => {
        if (demandeToCancel) {
            try {
                const response = await fetch(`http://localhost:8000/api/demandes/${demandeToCancel.id}/cancel`, {
                    method: 'POST',
                });
                if (response.ok) {
                    setDemandes(demandes.filter(d => d.id !== demandeToCancel.id));
                    setHistorique([...historique, { ...demandeToCancel, status: 'Annulée' }]);
                }
            } catch (error) {
                console.error('Error cancelling demande:', error);
            } finally {
                setShowModal(false);
                setDemandeToCancel(null);
            }
        }
    };

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
                    </nav>
                </div>
            </div>

            {/* Main content */}
            <div className="flex-1 p-6">
                <h1 className="text-3xl font-semibold text-gray-800">Dashboard Voyageur</h1>
                <div className="mt-6">
                    <h2 className="text-xl font-semibold text-gray-800">Suivi des demandes de réservations</h2>
                    <div className="mt-4">
                        {demandes.length === 0 ? (
                            <p className="text-gray-600">Aucune demande de réservation en cours.</p>
                        ) : (
                            <ul>
                                {demandes.map((demande) => (
                                    <li key={demande.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                        <h3 className="text-lg font-semibold">Propriété: {demande.property.name}</h3>
                                        <p>Date d'arrivée: {new Date(demande.dateArrivee).toLocaleDateString()}</p>
                                        <p>Date de départ: {new Date(demande.dateDepart).toLocaleDateString()}</p>
                                        <p>Statut: {demande.status}</p>
                                        <div className="mt-4 flex space-x-4">
                                            <button className="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">Modifier</button>
                                            <button onClick={() => handleCancel(demande)} className="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Annuler</button>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>

                <div className="mt-6">
                    <h2 className="text-xl font-semibold text-gray-800">Historique des réservations</h2>
                    <div className="mt-4">
                        {historique.length === 0 ? (
                            <p className="text-gray-600">Aucune réservation passée.</p>
                        ) : (
                            <ul>
                                {historique.map((historique) => (
                                    <li key={historique.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                        <h3 className="text-lg font-semibold">Propriété: {historique.property.name}</h3>
                                        <p>Date d'arrivée: {new Date(historique.dateArrivee).toLocaleDateString()}</p>
                                        <p>Date de départ: {new Date(historique.dateDepart).toLocaleDateString()}</p>
                                        <p>Statut: {historique.status}</p>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>
            </div>

            {showModal && (
                <ReactModal isOpen={showModal} onRequestClose={() => setShowModal(false)} className="Modal" overlayClassName="Overlay">
                    <h2 className="text-2xl font-semibold mb-4">Confirmation</h2>
                    <p>Êtes-vous sûr de vouloir annuler cette demande de réservation ?</p>
                    <div className="flex justify-around mt-4">
                        <button onClick={confirmCancel} className="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Oui</button>
                        <button onClick={() => setShowModal(false)} className="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">Non</button>
                    </div>
                </ReactModal>
            )}
        </div>
    );
};

export default VoyageurDashboard;
