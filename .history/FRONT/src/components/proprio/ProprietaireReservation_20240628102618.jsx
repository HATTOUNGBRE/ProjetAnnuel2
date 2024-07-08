import React, { useState, useEffect, useContext } from 'react';
import AuthContext from '../AuthContext';

const ProprietaireReservation = () => {
    const { userId } = useContext(AuthContext);
    const [demandes, setDemandes] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        if (!userId) return;

        const fetchDemandes = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/user-demandes-prestations/${userId}`);
                const data = await response.json();
                setDemandes(data);
                console.log(data);
                setLoading(false);
            } catch (error) {
                console.error('Erreur:', error);
                setLoading(false);
            }
        };

        fetchDemandes();
    }, [userId]);

    const cancelDemande = async (id) => {
        try {
            const response = await fetch(`http://localhost:8000/api/demande-prestations/${id}/cancel`, {
                method: 'POST',
            });

            if (response.ok) {
                setDemandes(demandes.map(demande => demande.id === id ? { ...demande, statut: 'annulée' } : demande));
            } else {
                console.error('Erreur lors de l\'annulation de la demande');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    };

    if (loading) {
        return <div>Chargement...</div>;
    }

    const now = new Date();

    const pendingDemandes = demandes.filter(demande => demande.statut === 'en attente' && new Date(demande.dateDEffet) >= now );
    const acceptedDemandes = demandes.filter(demande => demande.statut === 'acceptée' && new Date(demande.dateDEffet) >= now);
    const pastDemandes = demandes.filter(demande => new Date(demande.dateDEffet) < now);

    return (
        <div className="w-2/3 mx-auto flex flex-col flex-wrap justify-center mt-16 mb-16 p-8 bg-white shadow-lg rounded-lg">
            <h1 className="text-3xl font-semibold mb-8">Gestion des Demandes de Prestations</h1>

            {/* Section des demandes de prestation */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-yellow-600">Demandes de Prestation en Attente</h2>
                <ul className="divide-y divide-gray-200">
                    {pendingDemandes.map(demande => (
                        <li key={demande.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{demande.titre}</h3>
                                <p className="text-lg text-gray-600">{demande.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {demande.dateDEffet} <br />
                                </p>
                            </div>
                            <div className="space-x-2">
                                <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">En Attente d'Acceptation</button>
                                <button 
                                    onClick={() => cancelDemande(demande.id)} 
                                    className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition"
                                >
                                    Annuler
                                </button>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>

            {/* Section des prestations acceptées */}
            <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-green-600">Prestations Acceptées</h2>
                <ul className="divide-y divide-gray-200">
                    {acceptedDemandes.map(demande => (
                        <li key={demande.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{demande.titre}</h3>
                                <p className="text-lg text-gray-600">{demande.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {demande.dateDEffet} <br />
                                </p>
                                <p className={`text-sm ${
                                    demande.statut === 'acceptée' ? 'text-green-500' :
                                    'text-red-500'
                                }`}>
                                    {demande.statut}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>

            {/* Section des prestations passées */}
            <div className="p-6 bg-gray-100 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold mb-4 text-gray-600">Prestations Passées</h2>
                <ul className="divide-y divide-gray-200">
                    {pastDemandes.map(demande => (
                        <li key={demande.id} className="py-6 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-semibold text-gray-800">{demande.titre}</h3>
                                <p className="text-lg text-gray-600">{demande.user.name}</p>
                                <p className="text-sm text-gray-500">
                                    <strong>Date de début:</strong> {demande.dateDEffet} <br />
                                </p>
                                <p className={`text-sm ${
                                    demande.statut === 'passée' ? 'text-gray-500' :
                                    'text-red-500'
                                }`}>
                                    {demande.statut}
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
