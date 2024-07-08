import React, { useState } from 'react';

const CreatePrestataire = ({ isOpen, onClose, onSave }) => {
    const [type, setType] = useState('');
    const [tarif, setTarif] = useState('');
    const userId = document.cookie
        .split('; ')
        .find(row => row.startsWith('userId='))
        ?.split('=')[1];

    const handleSubmit = async (e) => {
        e.preventDefault();

        const prestataire = {
            type,
            tarif,
            user_id: userId
        };

        try {
            const response = await fetch('http://localhost:8000/api/prestataires', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(prestataire),
            });

            if (response.ok) {
                alert('Prestataire créé avec succès');
                onSave();
                onClose();
            } else {
                alert('Erreur lors de la création du prestataire');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    };

    if (!isOpen) {
        return null;
    }

    return (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
            <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 className="text-2xl font-bold mb-4">Créer Prestataire</h2>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label className="block text-gray-700 font-bold mb-2">Type</label>
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
                    <div className="mb-4">
                        <label className="block text-gray-700 font-bold mb-2">Tarif/h en €</label>
                        <input
                            type="number"
                            step="0.01"
                            value={tarif}
                            onChange={(e) => setTarif(e.target.value)}
                            className="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div className="flex justify-end space-x-4">
                        <button type="button" className="bg-gray-500 text-white px-4 py-2 rounded" onClick={onClose}>Annuler</button>
                        <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default CreatePrestataire;
