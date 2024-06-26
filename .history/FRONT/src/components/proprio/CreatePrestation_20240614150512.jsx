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
        <form onSubmit={handleSubmit}>
            <div>
                <label>Titre</label>
                <input
                    type="text"
                    value={titre}
                    onChange={(e) => setTitre(e.target.value)}
                />
            </div>
            <div>
                <label>Description</label>
                <textarea
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                />
            </div>
            <div>
                <label>Date d'effet</label>
                <input
                    type="datetime-local"
                    value={dateDEffet}
                    onChange={(e) => setDateDEffet(e.target.value)}
                />
            </div>
            <div>
                <label>Date de fin</label>
                <input
                    type="datetime-local"
                    value={dateDeFin}
                    onChange={(e) => setDateDeFin(e.target.value)}
                />
            </div>
            <button type="submit">Créer Prestation</button>
        </form>
    );
};

export default CreatePrestation;
