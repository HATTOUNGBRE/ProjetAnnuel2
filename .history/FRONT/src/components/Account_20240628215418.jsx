import React, { useState, useEffect, useContext } from 'react';
import AuthContext from './AuthContext';
import PrestataireDetails from './prestataire/PrestataireDetails';
import { Link } from 'react-router-dom';

const Account = ({ userId }) => {
  const { isLoggedIn } = useContext(AuthContext);
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchUser = async (id) => {
    try {
      const response = await fetch(`http://localhost:8000/api/user/${id}`);
      if (!response.ok) {
        throw new Error('Failed to fetch user data');
      }
      const data = await response.json();
      setUser(data);
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
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.created_at}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vous êtes</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{getUserType(user.category_user)}</td>
              </tr>
              <tr>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vérifié</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{user.verified ? 'Oui' : 'Non'}</td>
              </tr>
            </tbody>
          </table>
          <div className="mt-4">
            <Link to={`/account/edit/${user.id}`} className="bg-pcs-300 text-white py-2 px-4 rounded-lg hover:bg-pcs-400">
              Modifier
            </Link>
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
    </div>
  );
};

export default Account;
