import React, { useState, useEffect, useContext } from 'react';
import AuthContext from './AuthContext';
import PrestataireDetails from './prestataire/PrestataireDetails';
import Modal from 'react-modal';

Modal.setAppElement('#root'); // Configure react-modal to bind to the root element

const Account = ({ userId }) => {
  const { isLoggedIn } = useContext(AuthContext);
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [modalIsOpen, setModalIsOpen] = useState(false);

  const [formData, setFormData] = useState({
    name: '',
    surname: '',
    email: '',
    password: '',
    categoryUser: '',
    imageProfile: null,
    isVerified: false,

  });

  const fetchUser = async (id) => {
    try {
      const response = await fetch(`http://localhost:8000/api/user/${id}`);
      if (!response.ok) {
        throw new Error('Failed to fetch user data');
      }
      const data = await response.json();
      setUser(data);
      setFormData({
        name: data.name,
        surname: data.surname,
        email: data.email,
        password: '',
        categoryUser: data.category_user,
        imageProfile: data.image_profile,
        isVerified: data.isVerified,
        
      });
      console.log('User fetched:', data);
    } catch (error) {
      setError(error.message);
    } finally {
      setLoading(false);
    }
  };
  


  useEffect(() => {
    if (userId) {
      fetchUser(userId);
    } 
  }, [userId, isLoggedIn]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleFileChange = (e) => {
    setFormData({
      ...formData,
      imageProfile: e.target.files[0],
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const updatedData = new FormData();
    updatedData.append('name', formData.name);
    updatedData.append('surname', formData.surname);
    updatedData.append('email', formData.email);
    if (formData.password) {
      updatedData.append('password', formData.password);
    }
    updatedData.append('categoryUserId', formData.categoryUser);
    if (formData.imageProfile) {
      updatedData.append('imageProfile', formData.imageProfile);
    }

    try {
      const response = await fetch(`http://localhost:8000/api/user/${userId}/update`, {
        method: 'POST',
        body: updatedData,
      });

      if (response.ok) {
        alert('User updated successfully');
        setModalIsOpen(false);
        fetchUser(userId); // Refresh user data
      } else {
        const data = await response.json();
        alert('Error: ' + data.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('An error occurred while updating the user.');
    }
  };

  const getUserType = (categoryUserId) => {
    switch (categoryUserId) {
      case 1:
        return 'Propriétaire';
      case 2:
        return 'Voyageur';
      case 3:
        return 'Prestataire';
      default:
        return 'N/A';
    }
  };

  if (loading) {
    return <div className="flex items-center justify-center h-screen">Loading...</div>;
  }

  if (error) {
    return <div className="flex items-center justify-center h-screen text-red-500">{error}</div>;
  }

  if (!isLoggedIn || !userId) {
    return <div className="flex items-center justify-center h-screen">No user data</div>;
  }

  return (
    <div className="max-w-4xl mx-auto my-10 p-6 bg-white rounded-lg shadow-md">
      <h1 className="text-2xl font-semibold text-pcs-400 mb-4">Account Details</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="col-span-1">
          <table className="min-w-full divide-y divide-gray-200">
            <tbody className="bg-white divide-y divide-gray-200">
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">ID</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.id}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Name</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.name}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Surname</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.surname}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Email</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.email}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Inscrit.e depuis le</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{new Date(user.created_at).toLocaleDateString()}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vous êtes</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{getUserType(user.category_user)}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vérifié</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.isVerified ? 'Oui' : 'Non'}</td>
              </tr>
            </tbody>
          </table>
          <div className="mt-4">
            <button onClick={() => setModalIsOpen(true)} className="bg-pcs-300 text-white py-2 px-4 rounded-lg hover:bg-pcs-400">
              Modifier
            </button>
          </div>
        </div>
        <div className="col-span-1 flex justify-center items-center">
          {user.image_profile ? (
            <img
              src={user.image_profile}
              alt="Profile"
              className="w-32 h-32 rounded-full"
            />
          ) : (
            <div className="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
              <span className="text-gray-500">No Image</span>
            </div>
          )}
        </div>
      </div>
      {user.category_user === 3 && (
        <PrestataireDetails userId={userId} />
      )}
      
      <Modal
        isOpen={modalIsOpen}
        onRequestClose={() => setModalIsOpen(false)}
        contentLabel="Modifier les informations de l'utilisateur"
        className="max-w-4xl mx-auto my-10 p-6 bg-white rounded-lg shadow-md"
        overlayClassName="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      >
        <h2 className="text-2xl font-semibold text-pcs-400 mb-4">Modifier les informations</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Name:</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleInputChange}
              className="w-full px-3 py-2 border rounded"
            />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Surname:</label>
            <input
              type="text"
              name="surname"
              value={formData.surname}
              onChange={handleInputChange}
              className="w-full px-3 py-2 border rounded"
            />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Email:</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleInputChange}
              className="w-full px-3 py-2 border rounded"
            />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Password:</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleInputChange}
              className="w-full px-3 py-2 border rounded"
            />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Category:</label>
            <select
              name="categoryUser"
              value={formData.categoryUser}
              onChange={handleInputChange}
              className="w-full px-3 py-2 border rounded"
            >
              <option value="">Select Category</option>
              <option value="1">Propriétaire</option>
              <option value="2">Voyageur</option>
              <option value="3">Prestataire</option>
            </select>
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 font-bold mb-2">Profile Image:</label>
            <input
              type="file"
              name="imageProfile"
              onChange={handleFileChange}
              className="w-full px-3 py-2 border rounded"
            />
          </div>
          <button type="submit" className="bg-pcs-300 text-white py-2 px-4 rounded-lg hover:bg-pcs-400">
            Save
          </button>
        </form>
      </Modal>
    </div>
  );
};

export default Account;
