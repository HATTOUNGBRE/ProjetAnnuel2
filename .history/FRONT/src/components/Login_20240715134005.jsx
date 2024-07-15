import React, { useState, useContext, useEffect } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import AuthContext from './AuthContext';

const Login = () => {
    const { login } = useContext(AuthContext);
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [role, setRole] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();
    const location = useLocation();

    useEffect(() => {
        const searchParams = new URLSearchParams(location.search);
        const role = location.search.split('?')[1];
        if (role) {
            setRole(role);
        }
    }, [location]);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!role) {
            setError('Rôle non sélectionné');
            return;
        }

        try {
            console.log('Sending login request with:', { email, password, role });

            const response = await fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password, role }),
            });

            const result = await response.json();
            console.log('Received response:', result);

            if (response.ok) {
                const token = result.token;
                const userType = result.categoryType;
                const userId = result.userId;
                const userName = result.name;
                const userSurname = result.surname;
                const categoryUserId = result.category;

                // Update auth context
                login(token, userType, userId, userName, userSurname, result.category);

                console.log('Cookies set:', { authToken: token, role: userType, userId: userId, userName: userName, userSurname: userSurname });

                // Redirect to the dashboard based on user type
                navigate(result.redirect);
            } else {
                setError(result.message);
                console.error('Error during login:', result);
            }
        } catch (error) {
            console.error('Error during login:', error);
            setError('An error occurred. Please try again.');
        }
    };

    return (
        <div className="flex items-center justify-center min-h-screen bg-pcs-100">
            <div className="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg">
                <h2 className="text-2xl text-pcs-300 font-bold text-center">Connexion</h2>
                {role && <p className="text-center text-pcs-300">En tant que {role}</p>}
                <form className="space-y-6" onSubmit={handleSubmit}>
                    {error && <div className="text-red-500">{error}</div>}
                    <div>
                        <label htmlFor="email" className="block text-sm font-medium text-pcs-200">
                            Adresse Email
                        </label>
                        <div className="mt-1">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autoComplete="email"
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="password" className="block text-sm font-medium text-pcs-200">
                            Mot de passe
                        </label>
                        <div className="mt-1">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autoComplete="current-password"
                                required
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            className="w-full px-4 mb-4 py-2 text-sm font-medium text-white bg-pcs-300 border border-transparent rounded-md shadow-sm hover:bg-pcs-400"
                        >
                            Se connecter
                        </button>
                        <Link to="/forgot-password" className="text-sm text-pcs-300 hover:underline">
                            Mot de passe oublié ?
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default Login;
