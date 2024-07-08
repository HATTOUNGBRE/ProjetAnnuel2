import React from 'react';
import { Link } from 'react-router-dom';

function Contact() {
  return (
    <div className="flex h-screen">
      <div className="w-1/2 bg-pcs-700 flex items-center justify-center">
        <div className="text-white text-center px-6 py-8">
          <div className="bg-pcs-600 text-white rounded-lg p-3 mb-10 pt-3 pb-1 m-3 font-semibold "
          >
          <h1 className="text-4xl font-bold mb-4">Besoin d'aide ?</h1>
          <p className="text-lg font-normal	 mb-8">
            Vous êtes un voyageur, un propriétaire ou un prestataire ?<br />
            Vous avez une question ?
          </p>
          </div>
          <form className="flex flex-col w-full max-w-md mx-auto">
            <input
              type="text"
              placeholder="Nom"
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
            />
            
            <input
              type="text"
              placeholder="Prénom"
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              required
            />
            <input
              type="email"
              placeholder="Email"
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              required
            />
            <select
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              placeholder="Vous êtes..."
              required 
            >
             
              <option value="voyageur">Voyageur</option>
              <option value="proprietaire">Propriétaire</option>
              <option value="prestataire">Prestataire</option>

            </select>
            <textarea
              placeholder="Message"
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg h-32"
            />
            <button
              type="submit"
              className="bg-pcs-600 text-white rounded-lg p-3 font-semibold hover:bg-pcs-200 transition duration-300"
            >
              Envoyer
            </button>
          </form>
        </div>
      </div>
      <div
        className="w-1/2 bg-cover bg-center"
        style={{ backgroundImage: "url('/images/girl.jpg')" }}
      ></div>
    </div>
  );
}

export default Contact;
