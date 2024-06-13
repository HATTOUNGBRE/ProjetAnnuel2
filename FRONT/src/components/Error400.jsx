import React from 'react';
import { Link } from 'react-router-dom';

const Error400 = () => {
    return (
        <div className="flex items-center justify-center min-h-screen bg-pcs-100">
            <div className="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg text-center">
                <h2 className="text-2xl text-pcs-300 font-bold">Erreur 400</h2>
                <p className="text-pcs-300">Accès refusé. Vous n'avez pas l'autorisation d'accéder à cette page.</p>
                <Link to="/">
                    <button className="bg-pcs-300 hover:bg-pcs-400 text-white font-bold py-2 px-8 rounded">
                        Retour à l'accueil
                    </button>
                </Link>
            </div>
        </div>
    );
};

export default Error400;
