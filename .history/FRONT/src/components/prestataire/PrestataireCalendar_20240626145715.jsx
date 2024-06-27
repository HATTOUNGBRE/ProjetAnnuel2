import React, { useState, useEffect } from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';

const localizer = momentLocalizer(moment);

const PrestataireCalendar = () => {
  const [events, setEvents] = useState([]);

  useEffect(() => {
    const fetchPrestations = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/prestations');
        const data = await response.json();
        const eventList = data.map(prestation => ({
          title: `${prestation.titre} (${moment(prestation.dateDEffet).format('HH:mm')})`,
          start: new Date(prestation.dateDEffet),
          end: new Date(prestation.dateDeFin),
          color: prestation.statut === 'Terminée' ? 'green' : 'blue',
        }));
        setEvents(eventList);
      } catch (error) {
        console.error('Erreur:', error);
      }
    };

    fetchPrestations();
  }, []);

  const eventStyleGetter = (event) => {
    const backgroundColor = event.color;
    const style = {
      backgroundColor: backgroundColor,
      borderRadius: '0px',
      opacity: 0.8,
      color: 'white',
      border: '0px',
      display: 'block',
    };
    return {
      style: style,
    };
  };

  return (
    <div>
      <h2 className="text-2xl font-semibold mb-4">Calendrier des Prestations</h2>
      <Calendar
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 500 }}
        eventPropGetter={eventStyleGetter}
      />
    </div>
  );
};

export default PrestataireCalendar;
