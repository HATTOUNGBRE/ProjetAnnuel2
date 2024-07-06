import React, { useContext, useEffect, useState } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog, FaPlus } from 'react-icons/fa';
import { Link } from 'react-router-dom';
import Modal from 'react-modal';
import AuthContext from '../AuthContext';
import PropertyList from '../proprio/PropertyList';
import Calendar from './Calendar'; // Assurez-vous que le chemin est correct

const ProprietaireDashboard = () => {
    const { userId } = useContext(AuthContext);
    const [reloadKey, setReloadKey] = useState(0);
    const [activeDemandes, setActiveDemandes] = useState([]);
    const [modalIsOpen, setModalIsOpen] = useState(false);
    const [selectedProperty, setSelectedProperty] = useState(null);
    const [successMessage, setSuccessMessage] = useState('');
    const [totalProperties, setTotalProperties] = useState(0);
    const [occupiedProperties, setOccupiedProperties] = useState(0);
    const [vacantProperties, setVacantProperties] = useState(0);
    const [earnings, setEarnings] = useState(0);
    const [error, setError] = useState(null);
    const [demandesCount, setDemandesCount] = useState(0); // Nouvelle variable d'état

    const handleReload = () => {
        setReloadKey(prevKey => prevKey + 1);
    };

    const fetchData = async () => {
        try {
            const response = await fetch(`http://localhost:8000/api/proprietaire/${userId}/dashboard`);
            if (!response.ok) {
                throw new Error('Failed to fetch dashboard data');
            }
            const data = await response.json();
            console.log('Fetched dashboard data:', data);
            setTotalProperties(data.totalProperties);
            setOccupiedProperties(data.occupiedProperties);
            setVacantProperties(data.vacantProperties);
            setEarnings(data.earnings);
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            setError('Failed to fetch dashboard data. Make sure you are a proprietor.');
        }
    };

    const fetchActiveDemandes = async () => {
        try {
            const response = await fetch("http://localhost:8000/api/demandes/active", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userId })
            });
            if (!response.ok) {
                throw new Error('Failed to fetch active demandes');
            }
            const data = await response.json();
            console.log('Fetched active demandes:', data);
            setActiveDemandes(data);
            setDemandesCount(data.length); // Mettre à jour le nombre de demandes
        } catch (error) {
            console.error('Error fetching active demandes:', error);
            setError('Failed to fetch active demandes');
        }
    };

    useEffect(() => {
        fetchData();
        fetchActiveDemandes();
    }, []);

    const openModal = (property) => {
        console.log('Opening modal for property:', property); // Log the property details
        setSelectedProperty(property);
        setModalIsOpen(true);
    };

    const closeModal = () => {
        console.log('Closing modal');
        setModalIsOpen(false);
        setSelectedProperty(null);
    };

    const handleAccept = async (demandeId) => {
        try {
            const response = await fetch(`http://localhost:8000/api/demandes/${demandeId}/accept`, {
                method: 'POST',
            });

            if (!response.ok) {
                throw new Error('Failed to accept the demande');
            }

            setSuccessMessage('La demande a été acceptée avec succès.');
            console.log('Accepted demande with id:', demandeId);

            // Reload the active demandes after acceptance
            fetchActiveDemandes();

            // Clear the success message after a few seconds
            setTimeout(() => {
                setSuccessMessage('');
            }, 3000);
        } catch (error) {
            console.error('Error accepting the demande:', error);
        }
    };

    return (
        <div className="flex h-screen bg-pcs-100">
            {/* Sidebar */}
            <div className="w-64 bg-pcs-250 shadow-md">
                <div className="p-6">
                    <h1 className="text-3xl font-semibold text-gray-800">Dashboard Propriétaire</h1>
                    <nav className="mt-10">
                        <Link to="/" className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700">
                            <FaHome className="w-5 h-5" />
                            <span className="mx-4 font-medium">Home</span>
                        </Link>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaClipboardList className="w-5 h-5" />
                            <span className="mx-4 font-medium">Listings</span>
                        </a>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaUsers className="w-5 h-5" />
                            <span className="mx-4 font-medium">Tenants</span>
                        </a>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaCog className="w-5 h-5" />
                            <span className="mx-4 font-medium">Settings</span>
                        </a>
                        <Link to="/add-property" className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700">
                            <FaPlus className="w-5 h-5" />
                            <span className="mx-4 font-medium">Ajouter une propriété</span>
                        </Link>
                        <Link to="/create-prestation" className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700">   
                            <FaPlus className="w-5 h-5" />
                            <span className="mx-4 font-medium">Ajouter une prestation</span>
                        </Link>
                    </nav>
                </div>
            </div>

            {/* Main content */}
            <div className="flex-1 p-6 flex flex-col mb-18">
                <h2 className="text-3xl font-semibold text-gray-800 mb-2">📆 Vos Chiffres</h2>

                {/* Cards section */}
                <div className="mt-6">
                    <div className="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Total Properties</h4>
                            <p className="text-gray-600">{totalProperties}</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Occupied</h4>
                            <p className="text-gray-600">{occupiedProperties}</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Vacant</h4>
                            <p className="text-gray-600">{vacantProperties}</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Earnings</h4>
                            <p className="text-gray-600">{earnings} €</p>
                        </div>
                    </div>
                </div>

                {/* Active Demandes section */}
                <div className="mt-6 mb-18">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Demandes de Réservation ({demandesCount})</h2>
                    {successMessage && (
                        <p className="text-green-500 mb-4">{successMessage}</p>
                    )}
                    {activeDemandes.length === 0 ? (
                        <p className="text-gray-600">Aucune demande de réservation active.</p>
                    ) : (
                        <ul className='dmd_active'>
                        {activeDemandes.map(demande => (
                            <li key={demande.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                <h3 className="text-lg font-semibold">Propriété: {demande.property.name}</h3>
                                <p>Date d'arrivée: {new Date(demande.dateArrivee).toLocaleDateString()}</p>
                                <p>Date de départ: {new Date(demande.dateDepart).toLocaleDateString()}</p>
                                <p>Nombre de personnes: {demande.guestNb}</p>
                                <p>Prix total: {demande.totalPrice} €</p>
                                <p>Statut: {demande.status}</p>
                                <button 
                                    onClick={() => handleAccept(demande.id)}
                                    className="bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-600"
                                >
                                    Accepter
                                </button>
                            </li>
                        ))}
                    </ul>
                    )}
                </div>
                 {/* Properties list section */}
                 <div className="mt-6 mb-20">
                    <PropertyList onDelete={handleReload} onShowCalendar={openModal} />
                </div>
            </div>            </div>

            {modalIsOpen && (
                <Modal isOpen={modalIsOpen} onRequestClose={closeModal} className="Modal" overlayClassName="Overlay">
                    <h2 className="text-2xl font-semibold mb-4">Modifier la propriété</h2>
                    {selectedProperty && (
                        <div>
                            <h3>{selectedProperty.name}</h3>
                            {/* Ajoutez d'autres champs pour modifier les détails de la propriété */}
                        </div>
                    )}
                    <button onClick={closeModal} className="bg-pcs-400 text-white py-2 px-4 rounded-lg hover:bg-pcs-500 mt-4">Fermer</button>
                </Modal>
            )}
        </div>
    );
};

export default ProprietaireDashboard;
