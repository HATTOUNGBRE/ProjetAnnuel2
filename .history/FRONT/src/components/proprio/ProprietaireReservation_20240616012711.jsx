import React, { useState, useEffect, useContext } from 'react';
import AuthContext from '../AuthContext';

const ProprietaireReservation = () => {
    const { userId } = useContext(AuthContext);
    const [reservations, setReservations] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (!userId) return;

        const fetchReservations = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/user-reservations/${userId}`);
                const data = await response.json();
                setReservations(data);
                setLoading(false);
            } catch (error) {
                console.error('Erreur:', error);
                setLoading(false);
            }
        };

        fetchReservations();
    }, [userId]);

    if (loading) {
        return <div>Chargement...</div>;
    }

    const now = new Date();

    const pendingReservations = reservations.filter(reservation => reservation.statut === 'en attente' && reservation.valide === true);
    console.log(pendingReservations);
    const ongoingReservations = reservations.filter(reservation => reservation.statut === 'Confirmée' && new Date(reservation.dateDeFin) >= now);
    const pastReservations = reservations.filter(reservation => reservation.statut === 'Passée' || new Date(reservation.dateDeFin) < now);

    return (
        <div className="max-w-4xl mx-auto mt-16 mb-16 p-8 bg-white shadow-lg rounded-lg">
            <h1 className="text-3xl font-semibold mb-8">Gestion des Réservations</h1>

            {/* Section des demandes de réservation */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-yellow-600">Demandes de Réservation</h2>
                <ul className="divide-y divide-gray-200">
                    {pendingReservations.map(reservation => (
                        <li key={reservation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{reservation.titre}</h3>
                                <p className="text-lg text-gray-600">{reservation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {reservation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {reservation.dateDeFin}
                                </p>
                            </div>
                            <div className="space-x-2">
                                <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">En Attente d'Acceptation</button>
                                
                            </div>
                        </li>
                    ))}
                </ul>
            </div>

            {/* Section des réservations en cours */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-green-600">Réservations en Cours</h2>
                <ul className="divide-y divide-gray-200">
                    {ongoingReservations.map(reservation => (
                        <li key={reservation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{reservation.titre}</h3>
                                <p className="text-lg text-gray-600">{reservation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {reservation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {reservation.dateDeFin}
                                </p>
                                <p className={`text-sm ${
                                    reservation.statut === 'En attente' ? 'text-yellow-500' :
                                    reservation.statut === 'Confirmée' ? 'text-green-500' :
                                    'text-red-500'
                                }`}>
                                    {reservation.statut}
                                </p>
                            </div>
                            <div className="space-x-2">
                                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Check-in</button>
                                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Check-out</button>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>

            {/* Section des réservations passées */}
            <div className="p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-gray-600">Réservations Passées</h2>
                <ul className="divide-y divide-gray-200">
                    {pastReservations.map(reservation => (
                        <li key={reservation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{reservation.titre}</h3>
                                <p className="text-lg text-gray-600">{reservation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {reservation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {reservation.dateDeFin}
                                </p>
                                <p className={`text-sm ${
                                    reservation.statut === 'Passée' ? 'text-gray-500' :
                                    'text-red-500'
                                }`}>
                                    {reservation.statut}
                                </p>
                            </div>
                            <div className="space-x-2">
                                <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Voir Détails</button>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>
        </div>
    );
}

export default ProprietaireReservation;
