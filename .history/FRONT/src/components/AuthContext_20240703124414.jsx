import React, { createContext, useState, useEffect } from 'react';
import Cookies from 'js-cookie';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [userRole, setUserRole] = useState('');
  const [userId, setUserId] = useState(null);
  const [userName, setUserName] = useState('');
  const [userSurname, setUserSurname] = useState('');
  const [category, setCategoryUserId] = useState(null);

  const fetchUserDetails = async (userId) => {
    try {
      const response = await fetch(`http://localhost:8000/api/user/${userId}`);
      const data = await response.json();

      if (response.ok) {
        setUserId(data.id);
        setUserName(data.name);
        setUserSurname(data.surname);
        setCategoryUserId(data.category_user);
      } else {
        logout(); // Logout if fetching user details fails
      }
    } catch (error) {
      console.error('Error fetching user details:', error);
      logout();
    }
  };

  useEffect(() => {
    const token = Cookies.get('authToken');
    const storedUserRole = Cookies.get('role');
    const storedUserId = Cookies.get('userId');

    if (token && storedUserId) {
      setIsLoggedIn(true);
      setUserRole(storedUserRole || '');
      fetchUserDetails(storedUserId); // Fetch user details
    }
  }, []);

  const login = (token, role, userId, userName, userSurname, category) => {
    Cookies.set('authToken', token, { expires: 1 });
    Cookies.set('role', role, { expires: 1 });
    Cookies.set('userId', userId, { expires: 1 });
    Cookies.set('userName', userName, { expires: 1 });
    Cookies.set('userSurname', userSurname, { expires: 1 });
    Cookies.set('category', category, { expires: 1 });

    setIsLoggedIn(true);
    setUserRole(role);
    setUserId(userId);
    setUserName(userName);
    setUserSurname(userSurname);
    setCategoryUserId(category);
  };

  const logout = () => {
    Cookies.remove('authToken');
    Cookies.remove('role');
    Cookies.remove('userId');
    Cookies.remove('userName');
    Cookies.remove('userSurname');
    Cookies.remove('category');

    setIsLoggedIn(false);
    setUserRole('');
    setUserId(null);
    setUserName('');
    setUserSurname('');
    setCategoryUserId(null);
  };

  return (
    <AuthContext.Provider value={{ isLoggedIn, userRole, userId, userName, userSurname, category, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;
