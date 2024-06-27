import React, { useState } from 'react';

const ProprietairePrestation = () => {
  const [filter, setFilter] = useState('Toutes');
  
  const prestations = [
    { id: 1, titre: 'Nettoyage', statut: 'En attente' },
    { id: 2, titre: 'Gardien', statut: 'Acceptée' },
    { id: 3, titre: 'Jardinier', statut: 'Refusée' },
    { id: 4, titre: 'Cuisine', statut: 'En attente' },
    { id: 5, titre: 'Plombier', statut: 'Acceptée' },
    { id: 6, titre: 'Electricien', statut: 'Passée' },
    { id: 7, titre: 'Peintre', statut: 'Passée' },
  ];

  const filteredPrestations = filter === 'Toutes' ? prestations : prestations.filter(prestation => prestation.statut === filter);

  const pendingPrestations = prestations.filter(prestation => prestation.statut === 'En attente');
  const ongoingPrestations = prestations.filter(prestation => prestation.statut === 'Acceptée');
  const pastPrestations = prestations.filter(prestation => prestation.statut === 'Passée' || prestation.statut === 'Refusée');

  return (
    <div className="max-w-4xl mx-auto mt-16 mb-16 p-8 bg-white shadow-lg rounded-lg">
      <h1 className="text-3xl font-semibold mb-8">Gestion des Prestations</h1>
      
     

      {/* Section des demandes de prestations */}
      <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-yellow-600">Demandes de Prestations</h2>
        <ul className="divide-y divide-gray-200">
          {pendingPrestations.map(prestation => (
            <li key={prestation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
               
              </div>
              <div className="space-x-2">
                <button className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition"> {prestation.statut}</button>
              </div>
            </li>
          ))}
        </ul>
      </div>

      {/* Section des prestations en cours */}
      <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-green-600">Prestations en Cours</h2>
        <ul className="divide-y divide-gray-200">
          {ongoingPrestations.map(prestation => (
            <li key={prestation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
                <p className={`text-sm text-green-500`}>
                  {prestation.statut}
                </p>
              </div>
              <div className="space-x-2">
                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Modifier</button>
                <button className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Annuler</button>
              </div>
            </li>
          ))}
        </ul>
      </div>

      {/* Section des prestations passées */}
      <div className="p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-gray-600">Prestations Passées</h2>
        <ul className="divide-y divide-gray-200">
          {pastPrestations.map(prestation => (
            <li key={prestation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{prestation.titre}</h3>
                <p className={`text-sm ${
                  prestation.statut === 'Passée' ? 'text-gray-500' :
                  'text-red-500'
                }`}>
                  {prestation.statut}
                </p>
              </div>
              <div className="space-x-2">
                <button className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Voir Détails</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

export default ProprietairePrestation;
