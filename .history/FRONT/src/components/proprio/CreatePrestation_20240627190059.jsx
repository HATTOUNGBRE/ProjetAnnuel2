import React, { useState, useContext, useEffect } from 'react';
import AuthContext from '../AuthContext'; // Importez votre contexte d'authentification
import { Link } from 'react-router-dom';

const CreatePrestation = () => {
    const [titre, setTitre] = useState('');
    const [description, setDescription] = useState('');
    const [dateDEffet, setDateDEffet] = useState('');
    const [type, setType] = useState('');
    const [propertyId, setPropertyId] = useState('');
    const [properties, setProperties] = useState([]);
    const { userId } = useContext(AuthContext); // Utilisez le contexte pour obtenir l'ID de l'utilisateur

    useEffect(() => {
        // Fetch properties of the user
        const fetchProperties = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/properties/${userId}`);
                const data = await response.json();
                setProperties(data);
            } catch (error) {
                console.error('Erreur lors de la récupération des propriétés:', error);
            }
        };

        fetchProperties();
    }, [userId]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        const prestation = {
            titre,
            description,
            dateDEffet,
            type,
            propertyId,
            userId
        };

        try {
            const response = await fetch('http://localhost:8000/api/demande-prestations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(prestation),
            });

            if (response.ok) {
                alert('Demande de prestation créée avec succès');
            } else {
                alert('Erreur lors de la création de la demande de prestation');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    };

    return (
        <div className='m-8'>
            <form onSubmit={handleSubmit} className="max-w-md mx-auto bg-white p-8 shadow-md rounded-md">
                <h2 className="text-2xl font-bold mb-4">Créer une Demande de Prestation</h2>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">Titre</label>
                    <input
                        type="text"
                        value={titre}
                        onChange={(e) => setTitre(e.target.value)}
                        className="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea
                        value={description}
                        onChange={(e) => setDescription(e.target.value)}
                        className="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">Date d'effet</label>
                    <input
                        type="datetime-local"
                        value={dateDEffet}
                        onChange={(e) => setDateDEffet(e.target.value)}
                        className="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">Type de prestation</label>
                    <select
                        value={type}
                        onChange={(e) => setType(e.target.value)}
                        className="w-full px-3 py-2 border rounded"
                        required
                    >
                        <option value="">Sélectionnez un type</option>
                        <option value="ménage">Ménage</option>
                        <option value="électricité">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="taxi">Taxi</option>
                        <option value="coursier">Coursier</option>
                        <option value="gardien">Gardien</option>
                        <option value="jardinage">Jardinage</option>
                        <option value="réparation">Réparation</option>
                        <option value="blanchisserie">Blanchisserie</option>
                    </select>
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700 font-bold mb-2">Propriété</label>
                    <select
                        value={propertyId}
                        onChange={(e) => setPropertyId(e.target.value)}
                        className="w-full px-3 py-2 border rounded"
                        required
                    >
                        <option value="">Sélectionnez une propriété</option>
                        {properties.map((property) => (
                            <option key={property.id} value={property.id}>{property.name}</option>
                        ))}
                    </select>
                </div>
                <button type="submit" className="bg-pcs-600 text-white px-4 py-2 rounded hover:bg-pcs-700">Créer Prestation</button>
                <Link to="/components/dashboard/proprietaire" className="block text-center mt-4 text-pcs-600 hover:underline">
                    <button className="bg-pcs-200 text-white px-4 py-2 rounded hover:bg-pcs-250">Retour</button>
                </Link>
                <Link to="/proprietaire/prestation" className="block text-center mt-4 text-pcs-600 hover:underline">
                    <button className="bg-pcs-200 text-white px-4 py-2 rounded hover:bg-pcs-250">Voir vos demandes de prestations</button>
                </Link>
            </form>
        </div>
    );
};

export default CreatePrestation;
