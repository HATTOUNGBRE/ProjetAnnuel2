import React, { useState } from 'react';

const CreatePrestation = () => {
    const [titre, setTitre] = useState('');
    const [description, setDescription] = useState('');
    const [dateDEffet, setDateDEffet] = useState('');
    const [dateDeFin, setDateDeFin] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();

        const prestation = {
            titre,
            description,
            dateDEffet,
            dateDeFin
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
        <div className="flex justify-center items-center min-h-screen bg-gray-100">
            <div className="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                <h2 className="text-2xl font-bold mb-6 text-center text-gray-700">Créer une Prestation</h2>
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label htmlFor="titre" className="block text-gray-700 mb-2">Titre:</label>
                        <input
                            id="titre"
                            type="text"
                            value={titre}
                            onChange={(e) => setTitre(e.target.value)}
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                        />
                    </div>
                    <div className="mb-4">
                        <label htmlFor="description" className="block text-gray-700 mb-2">Description:</label>
                        <textarea
                            id="description"
                            value={description}
                            onChange={(e) => setDescription(e.target.value)}
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                        />
                    </div>
                    <div className="mb-4">
                        <label htmlFor="dateDEffet" className="block text-gray-700 mb-2">Date d'effet:</label>
                        <input
                            id="dateDEffet"
                            type="datetime-local"
                            value={dateDEffet}
                            onChange={(e) => setDateDEffet(e.target.value)}
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                        />
                    </div>
                    <div className="mb-4">
                        <label htmlFor="dateDeFin" className="block text-gray-700 mb-2">Date de fin:</label>
                        <input
                            id="dateDeFin"
                            type="datetime-local"
                            value={dateDeFin}
                            onChange={(e) => setDateDeFin(e.target.value)}
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                        />
                    </div>
                    <button type="submit" className="w-full bg-pcs-600 text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500 hover:bg-pcs-700 transition">Créer Prestation</button>
                </form>
            </div>
        </div>
    );
};

export default CreatePrestation;
