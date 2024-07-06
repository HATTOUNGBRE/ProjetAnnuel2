import React, { useState, useEffect, useContext } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog } from 'react-icons/fa';
import AuthContext from '../AuthContext';
import ReactModal from 'react-modal';

const VoyageurDashboard = () => {
    const { userId, userName, userSurname } = useContext(AuthContext);
    const [demandes, setDemandes] = useState([]);
    const [historique, setHistorique] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [selectedDemande, setSelectedDemande] = useState(null);
    const [dateArrivee, setDateArrivee] = useState('');
    const [dateDepart, setDateDepart] = useState('');
    const [guestNb, setGuestNb] = useState(1);
    const [totalPrice, setTotalPrice] = useState(0);
    const [error, setError] = useState('');

    useEffect(() => {
        const fetchDemandes = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/demandes/voyageur/${userId}`);
                const data = await response.json();
                setDemandes(data.filter(demande => demande.status !== 'Annulée'));
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

    const handleEditClick = (demande) => {
        setSelectedDemande(demande);
        setDateArrivee(demande.dateArrivee.split('T')[0]);
        setDateDepart(demande.dateDepart.split('T')[0]);
        setGuestNb(demande.guestNb);
        setTotalPrice(demande.totalPrice);
        setShowModal(true);
    };

    const handleUpdate = async () => {
        if (new Date(dateArrivee) >= new Date(dateDepart)) {
            setError('La date d\'arrivée doit être avant la date de départ');
            return;
        }

        const updatedTotalPrice = selectedDemande.property.price * (new Date(dateDepart) - new Date(dateArrivee)) / (1000 * 60 * 60 * 24);

        try {
            const response = await fetch(`http://localhost:8000/api/demandes/${selectedDemande.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    dateArrivee,
                    dateDepart,
                    guestNb,
                    totalPrice: updatedTotalPrice,
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to update reservation');
            }

            const data = await response.json();
            setDemandes(demandes.map(dem => (dem.id === selectedDemande.id ? data : dem)));
            setShowModal(false);
        } catch (error) {
            console.error('Error updating reservation:', error);
            setError('Failed to update reservation');
        }
    };

    const handleCancelClick = async (demandeId) => {
        if (window.confirm('Are you sure you want to cancel this reservation?')) {
            try {
                await fetch(`http://localhost:8000/api/demandes/${demandeId}/cancel`, {
                    method: 'PUT',
                });
                setDemandes(demandes.filter(dem => dem.id !== demandeId));
                setHistorique(historique.map(hist => (hist.id === demandeId ? { ...hist, status: 'Annulée' } : hist)));
            } catch (error) {
                console.error('Error cancelling reservation:', error);
            }
        }
    };

    const getStatus = (demande) => {
        const now = new Date();
        const dateArrivee = new Date(demande.dateArrivee);
        const dateDepart = new Date(demande.dateDepart);

        if (demande.status === 'Acceptée' && now >= dateArrivee && now <= dateDepart) {
            return 'Acceptée et en cours';
        }
        return demande.status;
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
                    <div className="mt-4 dmd_active">
                        {demandes.length === 0 ? (
                            <p className="text-gray-600">Aucune demande de réservation en cours.</p>
                        ) : (
                            <ul>
                                {demandes.map((demande) => (
                                    <li key={demande.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                        <h3 className="text-2xl font-semibold text-pcs-400">Votre identifiant de réservation: {demande.reservationNumber}</h3>
                                        <h2 className="text-xl font-semibold">Logement: {demande.property.name}</h2>
                                        <p>Date d'arrivée: {new Date(demande.dateArrivee).toLocaleDateString()}</p>
                                        <p>Date de départ: {new Date(demande.dateDepart).toLocaleDateString()}</p>
                                        <p>Nombre de personnes: {demande.guestNb}</p>
                                        <p>Prix total: {demande.totalPrice} €</p>
                                        <p>Statut: {getStatus(demande)}</p>
                                        <button onClick={() => handleEditClick(demande)} className="bg-blue-500 text-white py-1 px-3 rounded mt-2">Modifier</button>
                                        <button onClick={() => handleCancelClick(demande.id)} className="bg-red-500 text-white py-1 px-3 rounded mt-2 ml-2">Annuler</button>
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
                                        <p>Prix total: {historique.totalPrice} €</p>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>
            </div>

            {showModal && (
                <ReactModal isOpen={showModal} onRequestClose={() => setShowModal(false)} className="Modal" overlayClassName="Overlay">
                    <h2 className="text-2xl font-semibold mb-4">Modifier la réservation</h2>
                    <label className="block text-gray-700 font-bold mb-2">
                        Date d'arrivée:
                        <input type="date" value={dateArrivee} onChange={(e) => setDateArrivee(e.target.value)} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                    <label className="block text-gray-700 font-bold mb-2">
                        Date de départ:
                        <input type="date" value={dateDepart} onChange={(e) => setDateDepart(e.target.value)} required className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                    <label className="block text-gray-700 font-bold mb-2">
                        Nombre de personnes:
                        <input type="number" value={guestNb} onChange={(e) => setGuestNb(e.target.value)} required min="1" className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </label>
                    {error && <div className="text-red-500">{error}</div>}
                    <div className="flex justify-around mt-4">
                        <button onClick={handleUpdate} className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500">Modifier</button>
                        <button onClick={() => setShowModal(false)} className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500">Annuler</button>
                    </div>
                </ReactModal>
            )}
        </div>
    );
};

export default VoyageurDashboard;
