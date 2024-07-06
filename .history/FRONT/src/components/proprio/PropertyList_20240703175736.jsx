import React, { useState, useEffect, useContext } from 'react';
import { FaCalendarAlt, FaTrashAlt } from 'react-icons/fa';
import AuthContext from '../AuthContext';
import EditPropertyModal from './EditProperty';

const PropertyList = ({ onDelete, onShowCalendar }) => {
    const { userId } = useContext(AuthContext);
    const [properties, setProperties] = useState([]);
    const [categories, setCategories] = useState([]);
    const [editingProperty, setEditingProperty] = useState(null);

    const fetchProperties = async () => {
        try {
            const response = await fetch(`http://localhost:8000/api/properties/${userId}`);
            const data = await response.json();
            setProperties(data);
        } catch (error) {
            console.error('Error fetching properties:', error);
        }
    };

    const fetchCategories = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/categories');
            const data = await response.json();
            setCategories(data);
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };

    useEffect(() => {
        fetchProperties();
        fetchCategories();
    }, [userId]);

    const handleDelete = async (propertyId) => {
        try {
            const response = await fetch(`http://localhost:8000/api/properties/${propertyId}`, {
                method: 'DELETE',
            });

            if (response.ok) {
                setProperties(properties.filter(property => property.id !== propertyId));
                onDelete();
            } else {
                console.error('Failed to delete the property');
            }
        } catch (error) {
            console.error('Error deleting the property:', error);
        }
    };

    const handleSave = (updatedProperty) => {
        setProperties(properties.map(property => property.id === updatedProperty.id ? updatedProperty : property));
    };

    return (
        <div className="mt-6">
            <h3 className="text-2xl font-semibold text-gray-800 mb-4">Liste des Propriétés</h3>
            <div className="overflow-x-auto">
                <table className="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th className="py-2 text-left px-4 bg-gray-100">Nom</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Commune</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Description</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Prix</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Catégorie</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Nb de prs max</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Piscine</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Superficie (m²)</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Terrasse/Balcon</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Statut</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {properties.map((property) => (
                            <tr key={property.id}>
                                <td className="py-2 px-4 border-b">{property.name}</td>
                                <td className="py-2 px-4 border-b">{property.commune}</td>
                                <td className="py-2 px-4 border-b">{property.description}</td>
                                <td className="py-2 px-4 border-b">{property.price}</td>
                                <td className="py-2 px-4 border-b">{property.category ? property.category.name : 'N/A'}</td>
                                <td className="py-2 px-4 border-b">{property.maxPersons}</td>
                                <td className="py-2 px-4 border-b">{property.hasPool ? 'Oui' : 'Non'}</td>
                                <td className="py-2 px-4 border-b">{property.hasBalcony ? 'Oui' : 'Non'}</td>
                                <td className="py-2 px-4 border-b">{property.active ? 'Oui' : 'Inactive'}</td>
                                <td className="py-2 px-4 border-b space-x-2">
                                <button onClick={() => onShowCalendar(property)} className="btn btn-primary">
                                    <FaCalendarAlt />
                                </button>
                                    <button
                                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition"
                                        onClick={() => setEditingProperty(property)}
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        onClick={() => handleDelete(property.id)}
                                        className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition"
                                    >
                                       <FaTrashAlt />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {editingProperty && (
                <EditPropertyModal
                    property={editingProperty}
                    categories={categories}
                    onClose={() => setEditingProperty(null)}
                    onSave={handleSave}
                />
            )}
        </div>
    );
};

export default PropertyList;
