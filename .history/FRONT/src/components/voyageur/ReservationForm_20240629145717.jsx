import React, { useState } from 'react';

const ReservationForm = ({ property, onClose }) => {
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [numGuests, setNumGuests] = useState(1);

  const handleSubmit = (e) => {
    e.preventDefault();
    // Logic for reservation submission
    console.log('Reservation submitted:', { property, startDate, endDate, numGuests });
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div className="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 className="text-2xl font-semibold mb-4">Réserver {property.name}</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-gray-700 mb-2">Date de début</label>
            <input type="date" value={startDate} onChange={(e) => setStartDate(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 mb-2">Date de fin</label>
            <input type="date" value={endDate} onChange={(e) => setEndDate(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          </div>
          <div className="mb-4">
            <label className="block text-gray-700 mb-2">Nombre de voyageurs</label>
            <input type="number" value={numGuests} onChange={(e) => setNumGuests(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" required />
          </div>
          <button type="submit" className="bg-pcs-300 text-white py-2 px-4 rounded-lg">Réserver</button>
          <button type="button" className="ml-4 bg-gray-300 text-black py-2 px-4 rounded-lg" onClick={onClose}>Annuler</button>
        </form>
      </div>
    </div>
  );
};

export default ReservationForm;
