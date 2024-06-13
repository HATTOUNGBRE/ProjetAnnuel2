import React, { useState } from 'react';
import Modal from 'react-modal';
import { Link } from 'react-router-dom';

Modal.setAppElement('#root'); // Configure react-modal to bind to the root element

const Inscription = () => {
  const [name, setName] = useState('');
  const [surname, setSurname] = useState('');
  const [password, setPassword] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [imageProfile, setProfileImage] = useState(null);
  const [modalIsOpen, setModalIsOpen] = useState(false);

  const roleMapping = {
    proprietaire: 1,
    voyageur: 2,
    prestataire: 3,
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const categoryUserId = roleMapping[role] || null;

    if (!categoryUserId) {
      setError('Rôle invalide sélectionné');
      setSuccess('');
      return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('surname', surname);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('categoryUserId', categoryUserId);
    if (imageProfile) {
      formData.append('imageProfile', imageProfile);
    }

    try {
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        body: formData,
      });

      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        const data = await response.json();
        if (response.ok) {
          setSuccess(data.message);
          setError('');
          setModalIsOpen(true); // Open the modal on successful registration
        } else {
          setError(data.message);
          setSuccess('');
        }
      } else {
        const errorText = await response.text();
        console.error(errorText);
        setSuccess('');
      }
    } catch (error) {
      setError('Une erreur est survenue lors de la connexion au serveur.');
      setSuccess('');
      console.error(error);
    }
  };

  return (
    <div className="flex justify-center items-center min-h-screen bg-pcs-100">
      <div className="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 className="text-2xl font-bold mb-6 text-center text-pcs-300">Page d'Inscription</h2>
        <form onSubmit={handleSubmit}>
          {error && <div className="mb-4 text-red-500">{error}</div>}
          <div className="mb-4">
            <label htmlFor="name" className="block text-pcs-250 mb-2">Name:</label>
            <input
              id="name"
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-pcs-300"
            />
          </div>
          <div className="mb-4">
            <label htmlFor="surname" className="block text-pcs-250 mb-2">Surname:</label>
            <input
              id="surname"
              type="text"
              value={surname}
              onChange={(e) => setSurname(e.target.value)}
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-pcs-300"
            />
          </div>
          <div className="mb-4">
            <label htmlFor="password" className="block text-pcs-250 mb-2">Password:</label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-pcs-300"
            />
          </div>
          <div className="mb-6">
            <label htmlFor="email" className="block text-pcs-250 mb-2">Email:</label>
            <input
              id="email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-pcs-300"
            />
          </div>
          <div className="mb-6">
            <label htmlFor="role" className="block text-pcs-250 mb-2">Vous êtes:</label>
            <select
              id="role"
              value={role}
              onChange={(e) => setRole(e.target.value)}
              className="w-full px-4 py-2 border rounded-lg text-pcs-700 focus:outline-none focus:ring-4 focus:ring-pcs-300"
            >
              <option value="">Sélectionnez votre rôle</option>
              <option value="proprietaire">Propriétaire</option>
              <option value="voyageur">Voyageur</option>
              <option value="prestataire">Prestataire</option>
            </select>
          </div>
          <div className="mb-6">
            <label htmlFor="profileImage" className="block text-pcs-250 mb-2">Photo de profil (optionnelle):</label>
            <input
              id="profileImage"
              type="file"
              onChange={(e) => setProfileImage(e.target.files[0])}
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-4 focus:ring-pcs-300"
            />
          </div>
          <button
            type="submit"
            className="w-full bg-pcs-300 text-white py-2 px-4 rounded-lg hover:bg-pcs-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50"
          >
            Register
          </button>
        </form>
      </div>
      <Modal
        isOpen={modalIsOpen}
        onRequestClose={() => setModalIsOpen(false)}
        contentLabel="Inscription Réussie"
        className="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto my-40"
        overlayClassName="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      >
        <h2 className="text-2xl text-pcs-200 font-bold mb-4">Inscription Réussie</h2>
        <p className="mb-4 text-pcs-250">Votre inscription a été réussie. Vous pouvez maintenant vous connecter.</p>
        <div className="flex justify-end">
          <Link to="/components/catUser" className="bg-pcs-300 text-white py-2 px-4 rounded-lg hover:bg-pcs-400">
            Se Connecter
          </Link>
        </div>
      </Modal>
    </div>
  );
};

export default Inscription;
