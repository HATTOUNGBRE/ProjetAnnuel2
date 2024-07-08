import React, { useState, useEffect, useContext } from 'react';
import AuthContext from '../AuthContext';

const ProprietaireReservation = () => {
    const { userId } = useContext(AuthContext);
    const [prestations, setPrestations] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (!userId) return;

        const fetchPrestations = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/user-prestations/${userId}`);
                const data = await response.json();
                setPrestations(data);
                console.log(data);
                setLoading(false);
            } catch (error) {
                console.error('Erreur:', error);
                setLoading(false);
            }
        };

        fetchPrestations();
    }, [userId]);

    if (loading) {
        return <div>Chargement...</div>;
    }

    const now = new Date();

    const pendingPrestations = prestations.filter(prestation => prestation.statut === 'en attente' && new Date(prestation.dateDEffet) >= now && prestation.valide === true);
    const ongoingPrestations = prestations.filter(prestation => prestation.statut === 'Confirmée' && new Date(prestation.dateDeFin) >= now);
    const pastPrestations = prestations.filter(prestation => prestation.statut === 'Passée' || new Date(prestation.dateDeFin) < now);

    return (
        <div className="w-2/3 mx-auto flex flex-col flex-wrap justify-center mt-16 mb-16 p-8 bg-white shadow-lg rounded-lg">
            <h1 className="text-3xl font-semibold mb-8">Gestion des Prestations</h1>

            {/* Section des demandes de prestation */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-yellow-600">Demandes de Prestation</h2>
                <ul className="divide-y divide-gray-200">
                    {pendingPrestations.map(prestation => (
                        <li key={prestation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
                                <p className="text-lg text-gray-600">{prestation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {prestation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {prestation.dateDeFin}
                                </p>
                            </div>
                            <div className="space-x-2">
                                <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">En Attente d'Acceptation</button>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>

            {/* Section des prestations en cours */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-green-600">Prestations en Cours</h2>
                <ul className="divide-y divide-gray-200">
                    {ongoingPrestations.map(prestation => (
                        <li key={prestation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
                                <p className="text-lg text-gray-600">{prestation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {prestation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {prestation.dateDeFin}
                                </p>
                                <p className={`text-sm ${
                                    prestation.statut === 'En attente' ? 'text-yellow-500' :
                                    prestation.statut === 'Confirmée' ? 'text-green-500' :
                                    'text-red-500'
                                }`}>
                                    {prestation.statut}
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

            {/* Section des prestations passées */}
            <div className="p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-gray-600">Prestations Passées</h2>
                <ul className="divide-y divide-gray-200">
                    {pastPrestations.map(prestation => (
                        <li key={prestation.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
                                <p className="text-lg text-gray-600">{prestation.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {prestation.dateDEffet} <br />
                                    <strong>Date de fin:</strong> {prestation.dateDeFin}
                                </p>
                                <p className={`text-sm ${
                                    prestation.statut === 'Passée' ? 'text-gray-500' :
                                    'text-red-500'
                                }`}>
                                    {prestation.statut}
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
