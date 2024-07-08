import React, { useState } from 'react';

const EditPropertyModal = ({ property, categories, onClose, onSave }) => {
    const [name, setName] = useState(property.name);
    const [description, setDescription] = useState(property.description);
    const [price, setPrice] = useState(property.price);
    const [category, setCategory] = useState(property.category ? property.category.id : '');
    const [maxPersons, setMaxPersons] = useState(property.maxPersons);
    const [hasPool, setHasPool] = useState(property.hasPool);
    const [area, setArea] = useState(property.area);
    const [hasBalcony, setHasBalcony] = useState(property.hasBalcony);
    const [image, setImage] = useState(null);

    const handleSave = async () => {
        const payload = {
            name: name,
            description: description,
            price: price,
            category: category,
            maxPersons: maxPersons,
            hasPool: hasPool,
            area: area,
            hasBalcony: hasBalcony,
        };

        console.log('Payload to be sent:', payload);
        console.log('Property ID:', property.id);

        try {
            const response = await fetch(`http://localhost:8000/api/properties/${property.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (response.ok) {
                const updatedProperty = await response.json();
                onSave(updatedProperty);
                console.log('Property updated:', updatedProperty);
                onClose();
            } else {
                console.error('Failed to update the property');
            }
        } catch (error) {
            console.error('Error updating the property:', error);
        }
    };

    return (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50 overflow-auto">
            <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md max-h-screen overflow-y-auto">
                <h3 className="text-2xl font-semibold mb-4 text-gray-700">Modifier la Propriété</h3>
                <div className="mb-4">
                    <label htmlFor="name" className="block text-gray-700 mb-2">Titre:</label>
                    <input
                        id="name"
                        type="text"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
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
                    <label htmlFor="price" className="block text-gray-700 mb-2">Prix:</label>
                    <input
                        id="price"
                        type="number"
                        value={price}
                        onChange={(e) => setPrice(e.target.value)}
                        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="category" className="block text-gray-700 mb-2">Catégorie:</label>
                    <select
                        id="category"
                        value={category}
                        onChange={(e) => setCategory(e.target.value)}
                        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                    >
                        <option value="">Sélectionnez une catégorie</option>
                        {categories.map((cat) => (
                            <option key={cat.id} value={cat.id}>{cat.name}</option>
                        ))}
                    </select>
                </div>
                <div className="mb-4">
                    <label htmlFor="maxPersons" className="block text-gray-700 mb-2">Nombre de personnes max:</label>
                    <input
                        id="maxPersons"
                        type="number"
                        value={maxPersons}
                        onChange={(e) => setMaxPersons(e.target.value)}
                        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="hasPool" className="block text-gray-700 mb-2">Piscine:</label>
                    <input
                        id="hasPool"
                        type="checkbox"
                        checked={hasPool}
                        onChange={(e) => setHasPool(e.target.checked)}
                        className="mr-2 leading-tight"
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="area" className="block text-gray-700 mb-2">Superficie (m²):</label>
                    <input
                        id="area"
                        type="number"
                        value={area}
                        onChange={(e) => setArea(e.target.value)}
                        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="hasBalcony" className="block text-gray-700 mb-2">Terrasse/Balcon:</label>
                    <input
                        id="hasBalcony"
                        type="checkbox"
                        checked={hasBalcony}
                        onChange={(e) => setHasBalcony(e.target.checked)}
                        className="mr-2 leading-tight"
                    />
                </div>
                <div className="mb-4">
                    <label htmlFor="image" className="block text-gray-700 mb-2">Image:</label>
                    <input
                        id="image"
                        type="file"
                        onChange={(e) => setImage(e.target.files[0])}
                        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"
                    />
                </div>
                <div className="flex justify-end">
                    <button
                        className="bg-gray-500 text-white py-2 px-4 rounded-lg mr-2"
                        onClick={onClose}
                    >
                        Annuler
                    </button>
                    <button
                        className="bg-pcs-600 text-white py-2 px-4 rounded-lg"
                        onClick={handleSave}
                    >
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    );
};

export default EditPropertyModal;
