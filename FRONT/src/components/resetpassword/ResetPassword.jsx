// src/components/resetpassword/ResetPassword.jsx
import React, { useState } from 'react';
import axios from 'axios';
import { useParams } from 'react-router-dom';

const ResetPassword = () => {
    const { token } = useParams();
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post(`/api/reset-password/${token}`, { password });
            setMessage('Votre mot de passe a été réinitialisé avec succès.');
        } catch (error) {
            setMessage('Erreur lors de la réinitialisation du mot de passe.');
        }
    };

    return (
        <div>
            <h2>Réinitialiser le mot de passe</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Nouveau mot de passe:</label>
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                </div>
                <button type="submit">Réinitialiser</button>
            </form>
            {message && <p>{message}</p>}
        </div>
    );
};

export default ResetPassword;
