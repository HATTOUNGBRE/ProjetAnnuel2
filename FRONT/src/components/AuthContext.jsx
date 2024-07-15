import React, { createContext, useState, useEffect } from 'react';
import Cookies from 'js-cookie';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [userRole, setUserRole] = useState('');
  const [userId, setUserId] = useState(null);
  const [userName, setUserName] = useState('');
  const [userSurname, setUserSurname] = useState('');
  const [email, setEmail] = useState('');
  const [category, setCategoryUserId] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchUserDetails = async (userId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/user/${userId}`);
      const data = await response.json();

      if (response.ok) {
        setIsLoggedIn(true);
        setUserId(data.id);
        setUserName(data.name);
        setUserSurname(data.surname);
        setEmail(data.email);
        setCategoryUserId(data.category_user);
        setUserRole(Cookies.get('role'));
        console.log('User details fetched:', data); // Add this line
      } else {
        setIsLoggedIn(false);
        setUserId(null);
        setUserName('');
        setUserSurname('');
        setEmail('');
        setCategoryUserId(null);
        setUserRole('');
        console.log('Failed to fetch user details. Response:', response); // Add this line
      }
    } catch (error) {
      console.error('Error fetching user details:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const token = Cookies.get('authToken');
    const storedUserRole = Cookies.get('role');
    const storedUserId = Cookies.get('userId');

    if (token && storedUserId) {
      setIsLoggedIn(true);
      setUserRole(storedUserRole || '');
      fetchUserDetails(storedUserId);
    } else {
      setLoading(false);
    }
  }, []);

  const login = (token, role, userId, userName, userSurname, email, category) => {
    Cookies.set('authToken', token, { expires: 1 });
    Cookies.set('role', role, { expires: 1 });
    Cookies.set('userId', userId, { expires: 1 });
    Cookies.set('userName', userName, { expires: 1 });
    Cookies.set('userSurname', userSurname, { expires: 1 });
    Cookies.set('email', email, { expires: 1 });
    Cookies.set('category', category, { expires: 1 });

    setIsLoggedIn(true);
    setUserRole(role);
    setUserId(userId);
    setUserName(userName);
    setUserSurname(userSurname);
    setEmail(email);
    setCategoryUserId(category);
    setLoading(false);
    console.log('User logged in:', { userId, userName, userSurname, email }); // Add this line
  };

  const logout = () => {
    Cookies.remove('authToken');
    Cookies.remove('role');
    Cookies.remove('userId');
    Cookies.remove('userName');
    Cookies.remove('userSurname');
    Cookies.remove('email');
    Cookies.remove('category');

    setIsLoggedIn(false);
    setUserRole('');
    setUserId(null);
    setUserName('');
    setUserSurname('');
    setEmail('');
    setCategoryUserId(null);
    setLoading(false);
    console.log('User logged out'); // Add this line
  };

  return (
    <AuthContext.Provider value={{ isLoggedIn, userRole, userId, userName, userSurname, email, category, login, logout, fetchUserDetails, loading }}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;
