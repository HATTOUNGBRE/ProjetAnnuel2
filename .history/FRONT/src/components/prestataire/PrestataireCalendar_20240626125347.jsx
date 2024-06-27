// src/components/PrestataireCalendar.jsx

import React, { useState, useEffect, useContext } from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import AuthContext from '../AuthContext';

const localizer = momentLocalizer(moment);

const PrestataireCalendar = () => {
  const { userId } = useContext(AuthContext);
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

 

  const handleSelectSlot = async ({ start, end }) => {
    try {
      const response = await fetch('http://localhost:8000/api/disponibilites/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          prestataire_id: userId,
          start: start.toISOString(),
          end: end.toISOString(),
        }),
      });

      if (!response.ok) {
        throw new Error('Failed to add availability');
      }

      const newEvent = await response.json();
      setEvents([...events, newEvent]);
    } catch (error) {
      setError(error.message);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="p-4 bg-white rounded-lg shadow-md">
      <h2 className="text-2xl font-bold mb-4">Disponibilités du Prestataire</h2>
      <Calendar
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: '30em' }}
        selectable
        onSelectSlot={handleSelectSlot}
      />
    </div>
  );
};

export default PrestataireCalendar;
