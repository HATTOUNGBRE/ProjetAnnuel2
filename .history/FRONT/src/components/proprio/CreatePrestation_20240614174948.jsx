import React, { useState, useContext } from 'react';
import AuthContext from './AuthContext'; // Importez votre contexte d'authentification

const CreatePrestation = () => {
    const [titre, setTitre] = useState('');
    const [description, setDescription] = useState('');
    const [dateDEffet, setDateDEffet] = useState('');
    const [type, setType] = useState('');
    const { userId } = useContext(AuthContext); // Utilisez le contexte pour obtenir l'ID de l'utilisateur

    const handleSubmit = async (e) => {
        e.preventDefault();

        const prestation = {
            titre,
            description,
            dateDEffet,
            type,
            userId
        };

        try {
            const response = await fetch('http://localhost:8000/api/prestations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(prestation),
            });

            if (response.ok) {
                alert('Prestation créée avec succès');
            } else {
                alert('Erreur lors de la création de la prestation');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="max-w-md mx-auto bg-white p-8 shadow-md rounded-md">
            <h2 className="text-2xl font-bold mb-4">Créer Prestation</h2>
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
                </select>
            </div>
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Créer Prestation</button>
        </form>
    );
};

export default CreatePrestation;
