import React, { useState, useEffect } from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';

const localizer = momentLocalizer(moment);

const PrestataireCalendar = ({ prestations, onStatusChange }) => {
  const [events, setEvents] = useState([]);

  useEffect(() => {
    const calendarEvents = prestations.map(prestation => ({
      title: prestation.titre,
      start: new Date(prestation.dateDEffet),
      end: prestation.dateDeFin ? new Date(prestation.dateDeFin) : new Date(prestation.dateDEffet),
      allDay: true,
      resource: prestation
    }));
    setEvents(calendarEvents);
  }, [prestations]);

  const handleSelectEvent = event => {
    const prestation = event.resource;
    if (window.confirm(`Mark prestation "${prestation.titre}" as completed?`)) {
      onStatusChange(prestation.id, 'completed');
    }
  };

  return (
    <div className="bg-white p-4 rounded-lg shadow-md">
      <h2 className="text-2xl font-semibold mb-4">Calendrier des Prestations</h2>
      <Calendar
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 500 }}
        selectable
        onSelectEvent={handleSelectEvent}
      />
    </div>
  );
};

export default PrestataireCalendar;
