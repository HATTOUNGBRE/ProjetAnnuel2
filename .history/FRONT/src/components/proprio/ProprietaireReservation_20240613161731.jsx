import React from 'react';

// Contenu fictif des réservations
const reservations = [
  {
    id: 1,
    guestName: 'John Doe',
    apartment: 'Appartement Deluxe - Paris',
    checkIn: '2024-06-01',
    checkOut: '2024-06-07',
    status: 'En attente'
  },
  {
    id: 2,
    guestName: 'Jane Smith',
    apartment: 'Appartement Standard - Lyon',
    checkIn: '2024-07-10',
    checkOut: '2024-07-15',
    status: 'Confirmée'
  },
  {
    id: 3,
    guestName: 'Alice Johnson',
    apartment: 'Studio - Marseille',
    checkIn: '2024-08-20',
    checkOut: '2024-08-25',
    status: 'Annulée'
  },
  {
    id: 4,
    guestName: 'Robert Brown',
    apartment: 'Loft - Bordeaux',
    checkIn: '2024-09-05',
    checkOut: '2024-09-10',
    status: 'En attente'
  },
  {
    id: 5,
    guestName: 'Emily Davis',
    apartment: 'Villa - Nice',
    checkIn: '2024-10-12',
    checkOut: '2024-10-17',
    status: 'Confirmée'
  },
  {
    id: 6,
    guestName: 'George White',
    apartment: 'Maison - Lille',
    checkIn: '2023-05-01',
    checkOut: '2023-05-07',
    status: 'Passée'
  },
  {
    id: 7,
    guestName: 'Sophia Green',
    apartment: 'Chalet - Chamonix',
    checkIn: '2023-04-15',
    checkOut: '2023-04-20',
    status: 'Passée'
  }
];

const ProprietaireReservation = () => {
  const now = new Date();

  const pendingReservations = reservations.filter(reservation => reservation.status === 'En attente');
  const ongoingReservations = reservations.filter(reservation => reservation.status === 'Confirmée' && new Date(reservation.checkOut) >= now);
  const pastReservations = reservations.filter(reservation => reservation.status === 'Passée' || new Date(reservation.checkOut) < now);

  return (
    <div className="max-w-4xl mx-auto mt-16 mb-16 p-8 bg-white shadow-lg rounded-lg">
      <h1 className="text-3xl font-semibold mb-8">Gestion des Réservations</h1>

      {/* Section des demandes de réservation */}
      <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-yellow-600">Demandes de Réservation</h2>
        <ul className="divide-y divide-gray-200">
          {pendingReservations.map(reservation => (
            <li key={reservation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{reservation.apartment}</h3>
                <p className="text-lg text-gray-600">{reservation.guestName}</p>
                <p className="text-sm text-gray-500">
                  <strong>Check-in:</strong> {reservation.checkIn} <br />
                  <strong>Check-out:</strong> {reservation.checkOut}
                </p>
              </div>
              <div className="space-x-2">
                <button className="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">Accepter</button>
                <button className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Refuser</button>
              </div>
            </li>
          ))}
        </ul>
      </div>

      {/* Section des réservations en cours */}
      <div className="mb-16 p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-green-600">Réservations en Cours</h2>
        <ul className="divide-y divide-gray-200">
          {ongoingReservations.map(reservation => (
            <li key={reservation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{reservation.apartment}</h3>
                <p className="text-lg text-gray-600">{reservation.guestName}</p>
                <p className="text-sm text-gray-500">
                  <strong>Check-in:</strong> {reservation.checkIn} <br />
                  <strong>Check-out:</strong> {reservation.checkOut}
                </p>
                <p className={`text-sm ${
                  reservation.status === 'En attente' ? 'text-yellow-500' :
                  reservation.status === 'Confirmée' ? 'text-green-500' :
                  'text-red-500'
                }`}>
                  {reservation.status}
                </p>
              </div>
              <div className="space-x-2">
                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Check-in</button>
                <button className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Check-out</button>
              </div>
            </li>
          ))}
        </ul>
      </div>

      {/* Section des réservations passées */}
      <div className="p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 className="text-2xl font-semibold mb-4 text-gray-600">Réservations Passées</h2>
        <ul className="divide-y divide-gray-200">
          {pastReservations.map(reservation => (
            <li key={reservation.id} className="py-6 flex justify-between items-center">
              <div>
                <h3 className="text-xl font-semibold text-gray-800">{reservation.apartment}</h3>
                <p className="text-lg text-gray-600">{reservation.guestName}</p>
                <p className="text-sm text-gray-500">
                  <strong>Check-in:</strong> {reservation.checkIn} <br />
                  <strong>Check-out:</strong> {reservation.checkOut}
                </p>
                <p className={`text-sm ${
                  reservation.status === 'Passée' ? 'text-gray-500' :
                  'text-red-500'
                }`}>
                  {reservation.status}
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

export default ProprietaireReservation;
