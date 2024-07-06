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

    const handleReload = () => {
        setReloadKey(prevKey => prevKey + 1);
    };

    const fetchActiveDemandes = async () => {
        try {
            const response = await fetch("http://localhost:8000/api/demandes/active");
            const data = await response.json();
            setActiveDemandes(data);
        } catch (error) {
            console.error('Error fetching active demandes:', error);
        }
    };

    useEffect(() => {
        fetchActiveDemandes();
    }, []);

    const openModal = (property) => {
        setSelectedProperty(property);
        setModalIsOpen(true);
    };

    const closeModal = () => {
        setModalIsOpen(false);
        setSelectedProperty(null);
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
            <div className="flex-1 p-6 flex flex-col mb-auto">
                <h2 className="text-3xl font-semibold text-gray-800 mb-2">📆 Vos Chiffres</h2>

                {/* Cards section */}
                <div className="mt-6">
                    <div className="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Total Properties</h4>
                            <p className="text-gray-600">12</p>
                            <h6 className="text-gray-600">+2 from last month</h6>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Occupied</h4>
                            <p className="text-gray-600">8</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Vacant</h4>
                            <p className="text-gray-600">4</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Earnings</h4>
                            <p className="text-gray-600">$24,000</p>
                        </div>
                    </div>
                </div>

                {/* Active Demandes section */}
                <div className="mt-6">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Demandes de Réservation Actives</h2>
                    {activeDemandes.length === 0 ? (
                        <p className="text-gray-600">Aucune demande de réservation active.</p>
                    ) : (
                        <ul>
                            {activeDemandes.map(demande => (
                                <li key={demande.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                    <h3 className="text-lg font-semibold">Propriété: {demande.property.name}</h3>
                                    <p>Date d'arrivée: {new Date(demande.dateArrivee).toLocaleDateString()}</p>
                                    <p>Date de départ: {new Date(demande.dateDepart).toLocaleDateString()}</p>
                                    <p>Nombre de personnes: {demande.guestNb}</p>
                                    <p>Prix total: {demande.totalPrice} €</p>
                                    <p>Statut: {demande.status}</p>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {/* Properties list section */}
                <div className="mt-6">
                    <PropertyList onDelete={handleReload} onShowCalendar={openModal} />
                </div>
            </div>

            {/* Modal */}
            <Modal
                isOpen={modalIsOpen}
                onRequestClose={closeModal}
                contentLabel="Calendar Modal"
                className="Modal"
                overlayClassName="Overlay"
            >
                <button onClick={closeModal} className="close-button">Close</button>
                {selectedProperty && (
                    <div>
                        <h2>{selectedProperty.name}</h2>
                        <Calendar property={selectedProperty} />
                    </div>
                )}
            </Modal>
        </div>
    );
};

export default ProprietaireDashboard;
