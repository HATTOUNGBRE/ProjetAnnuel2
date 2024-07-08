import React, { useState, useEffect, useContext } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import AuthContext from '../AuthContext';
import ReactModal from 'react-modal';

const VoyageurDashboard = () => {
    const { userId } = useContext(AuthContext);
    const [demandes, setDemandes] = useState([]);
    const [historique, setHistorique] = useState([]);
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [demandeToCancel, setDemandeToCancel] = useState(null);
    const [demandeToEdit, setDemandeToEdit] = useState(null);
    const [editDateArrivee, setEditDateArrivee] = useState('');
    const [editDateDepart, setEditDateDepart] = useState('');
    const [editGuestNb, setEditGuestNb] = useState(1);
    const [editError, setEditError] = useState('');

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
        setShowCancelModal(true);
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
                setShowCancelModal(false);
                setDemandeToCancel(null);
            }
        }
    };

    const handleEdit = (demande) => {
        setDemandeToEdit(demande);
        setEditDateArrivee(demande.dateArrivee);
        setEditDateDepart(demande.dateDepart);
        setEditGuestNb(demande.guestNb);
        setShowEditModal(true);
    };

    const confirmEdit = async () => {
        if (new Date(editDateArrivee) >= new Date(editDateDepart)) {
            setEditError('La date d\'arrivée doit être avant la date de départ');
            return;
        }

        try {
            const response = await fetch(`http://localhost:8000/api/demandes/${demandeToEdit.id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    dateArrivee: editDateArrivee,
                    dateDepart: editDateDepart,
                    guestNb: editGuestNb,
                }),
            });

            if (response.ok) {
                const updatedDemande = await response.json();
                setDemandes(demandes.map(d => d.id === demandeToEdit.id ? updatedDemande : d));
                setShowEditModal(false);
                setDemandeToEdit(null);
                setEditError('');
            } else {
                setEditError('Failed to update demande');
            }
        } catch (error) {
            console.error('Error updating demande:', error);
            setEditError('Failed to update demande');
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
                                        <button onClick={() => handleEdit(demande)} className="bg-blue-500 text-white py-1 px-3 rounded-md">Modifier</button>
                                        <button onClick={() => handleCancel(demande)} className="bg-red-500 text-white py-1 px-3 rounded-md ml-2">Annuler</button>
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

            {/* Cancel Confirmation Modal */}
            <ReactModal isOpen={showCancelModal} onRequestClose={() => setShowCancelModal(false)} className="Modal" overlayClassName="Overlay">
                <h2 className="text-2xl font-semibold mb-4">Confirmation d'annulation</h2>
                <p className="mb-4">Êtes-vous sûr de vouloir annuler cette demande de réservation ?</p>
                <div className="flex justify-around">
                    <button onClick={confirmCancel} className="bg-red-500 text-white py-2 px-4 rounded-lg">Oui, annuler</button>
                    <button onClick={() => setShowCancelModal(false)} className="bg-gray-500 text-white py-2 px-4 rounded-lg">Non, revenir</button>
                </div>
            </ReactModal>

            {/* Edit Modal */}
            <ReactModal isOpen={showEditModal} onRequestClose={() => setShowEditModal(false)} className="Modal" overlayClassName="Overlay">
                <h2 className="text-2xl font-semibold mb-4">Modifier la demande de réservation</h2>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Date d'arrivée:
                        <input type="date" value={editDateArrivee} onChange={(e) => setEditDateArrivee(e.target.value)} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Date de départ:
                        <input type="date" value={editDateDepart} onChange={(e) => setEditDateDepart(e.target.value)} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">
                        Nombre de personnes:
                        <input type="number" value={editGuestNb} onChange={(e) => setEditGuestNb(e.target.value)} required min="1" className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                </div>
                {editError && <div className="text-red-500">{editError}</div>}
                <div className="flex justify-around">
                    <button onClick={confirmEdit} className="bg-blue-500 text-white py-2 px-4 rounded-lg">Enregistrer</button>
                    <button onClick={() => setShowEditModal(false)} className="bg-gray-500 text-white py-2 px-4 rounded-lg">Annuler</button>
                </div>
            </ReactModal>
        </div>
    );
};

export default VoyageurDashboard;
