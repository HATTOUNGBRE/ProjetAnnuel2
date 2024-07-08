import React, { useState, useEffect, useContext } from 'react';
import AuthContext from '../AuthContext';
import { useNavigate, Link } from 'react-router-dom';

const AddProperty = () => {
  const { userId, category } = useContext(AuthContext);
  const navigate = useNavigate();
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [price, setPrice] = useState('');
  const [propertyCategory, setCategory] = useState('');
  const [categories, setCategories] = useState([]);
  const [maxPersons, setMaxPersons] = useState('');
  const [hasPool, setHasPool] = useState(false);
  const [area, setArea] = useState('');
  const [hasBalcony, setHasBalcony] = useState(false);
  const [image, setImage] = useState(null);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [showModal, setShowModal] = useState(false);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/categories');
        const data = await response.json();
        setCategories(data);
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    };

    fetchCategories();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (parseInt(category) !== 1) {
      setError("Vous n'êtes pas autorisé à ajouter une propriété.");
      console.log("User ID:", userId);
      console.log("Category User ID:", category);
      return;
    }

    const propertyData = {
      name,
      description,
      price,
      category: propertyCategory,
      proprio: userId,
      maxPersons,
      hasPool,
      area,
      hasBalcony,
      image // You might need to handle image upload separately if not using FormData
    };
    console.log('Property Data:', propertyData);

    try {
      const response = await fetch('http://localhost:8000/api/properties', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(propertyData),
      });

      const data = await response.json();
      if (response.ok) {
        setSuccess('Propriété ajoutée avec succès');
        setError('');
        setShowModal(true);
      } else {
        setError(data.message);
        setSuccess('');
      }
    } catch (error) {
      setError('Une erreur est survenue lors de la connexion au serveur.');
      console.error('Error adding property:', error);
      setSuccess('');
    }
  };

  return (
    <div className="flex justify-center items-center min-h-screen bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 className="text-2xl font-bold mb-6 text-center text-gray-700">Ajouter une Propriété</h2>
        <form onSubmit={handleSubmit}>
          {error && <div className="mb-4 text-red-500">{error}</div>}
          {success && <div className="mb-4 text-green-500">{success}</div>}
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
              value={propertyCategory}
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
          <button
            type="submit"
            className="w-full mb-3 bg-pcs-400 text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50"
          >
            Ajouter
          </button>
          <Link to="/components/dashboard/proprietaire">
            <button
              className="w-full bg-pcs-600 text-white py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50"
            >
              Retour au DashBoard
            </button>
          </Link>
        </form>
      </div>

      {showModal && (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 className="text-2xl font-semibold mb-4 text-gray-700">Succès</h3>
            <p className="mb-4 text-pcs-300">Propriété ajoutée avec succès.</p>
            <div className="flex justify-end">
              <button
                className="bg-gray-500 text-white py-2 px-4 rounded-lg mr-2"
                onClick={() => setShowModal(false)}
              >
                Annuler
              </button>
              <Link to="/components/dashboard/proprietaire">
                <button className="bg-pcs-600 text-white py-2 px-4 rounded-lg">
                  Aller au Dashboard
                </button>
              </Link>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default AddProperty;
