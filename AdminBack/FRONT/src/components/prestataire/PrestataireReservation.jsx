import React from 'react';

const reservations = [
  {
    id: 1,
    guestName: 'John Doe',
    service: 'Hotel Room - Deluxe',
    checkIn: '2024-06-01',
    checkOut: '2024-06-07',
    status: 'Confirmed'
  },
  {
    id: 2,
    guestName: 'Jane Smith',
    service: 'Airbnb Apartment - City Center',
    checkIn: '2024-07-10',
    checkOut: '2024-07-15',
    status: 'Pending'
  },
  {
    id: 3,
    guestName: 'Alice Johnson',
    service: 'Bed & Breakfast - Cozy Room',
    checkIn: '2024-08-20',
    checkOut: '2024-08-25',
    status: 'Cancelled'
  }
];

const PrestataireReservation = () => {
  return (
    <div className="p-8 bg-gray-100 min-h-screen">
      <h1 className="text-3xl font-semibold mb-6">Reservations</h1>
      <div className="bg-white shadow rounded-lg p-6">
        <table className="min-w-full table-auto">
          <thead>
            <tr>
              <th className="px-4 py-2 text-left">Guest Name</th>
              <th className="px-4 py-2 text-left">Service</th>
              <th className="px-4 py-2 text-left">Check-In</th>
              <th className="px-4 py-2 text-left">Check-Out</th>
              <th className="px-4 py-2 text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            {reservations.map(reservation => (
              <tr key={reservation.id} className="bg-gray-50 even:bg-gray-100">
                <td className="border px-4 py-2">{reservation.guestName}</td>
                <td className="border px-4 py-2">{reservation.service}</td>
                <td className="border px-4 py-2">{reservation.checkIn}</td>
                <td className="border px-4 py-2">{reservation.checkOut}</td>
                <td className="border px-4 py-2">
                  <span
                    className={`px-2 py-1 rounded-full text-xs ${
                      reservation.status === 'Confirmed'
                        ? 'bg-green-100 text-green-800'
                        : reservation.status === 'Pending'
                        ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-red-100 text-red-800'
                    }`}
                  >
                    {reservation.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default PrestataireReservation;
