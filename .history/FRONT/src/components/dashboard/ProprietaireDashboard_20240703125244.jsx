import React, { useContext, useEffect, useState } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog, FaPlus } from 'react-icons/fa';
import { Link } from 'react-router-dom';
import AuthContext from '../AuthContext';
import PropertyList from '../proprio/PropertyList';

const ProprietaireDashboard = () => {
    const { userId } = useContext(AuthContext);
    const [reloadKey, setReloadKey] = useState(0);
    const [activeReservations, setActiveReservations] = useState([]);


    const handleReload = () => {
        setReloadKey(prevKey => prevKey + 1);
    };

    const fetchActiveReservations = async () => {
        try {
            const response = await fetch("http://localhost:8000/api/demandes/active");
            const data = await response.json();
            setActiveReservations(data);
        } catch (error) {
            console.error('Error fetching active reservations:', error);
        }
    };

    useEffect(() => {
        fetchActiveReservations();
    }, []);

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
            <div className="flex-1 p-6">
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

                {/* Active Reservations section */}
                <div className="mt-6">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Demandes de Réservation Actives</h2>
                    {activeReservations.length === 0 ? (
                        <p className="text-gray-600">Aucune demande de réservation active.</p>
                    ) : (
                        <ul>
                            {activeReservations.map(reservation => (
                                <li key={reservation.id} className="bg-white shadow-md rounded-lg p-4 mb-4">
                                    <h3 className="text-lg font-semibold">Propriété: {reservation.property.name}</h3>
                                    <p>Date d'arrivée: {new Date(reservation.dateArrivee).toLocaleDateString()}</p>
                                    <p>Date de départ: {new Date(reservation.dateDepart).toLocaleDateString()}</p>
                                    <p>Nombre de personnes: {reservation.guestNb}</p>
                                    <p>Prix total: {reservation.totalPrice} €</p>
                                    <p>Statut: {reservation.status}</p>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {/* Properties list section */}
                <div className="mt-6">
                    <PropertyList onDelete={handleReload} />
                </div>
            </div>
        </div>
    );
};

export default ProprietaireDashboard;