import React from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';

const localizer = momentLocalizer(moment);

const events = [
  {
    title: 'My Event',
    start: new Date(),
    end: new Date(moment().add(1, "days"))
  }
];

const Calendrier = () => {
  return (
    <div className="p-4 bg-white rounded-lg shadow-md">
      <h2 className="text-2xl font-bold mb-4">Disponibilité du Prestataire</h2>
      <div style={{ height: '100%' }}>
        <Calendar
          localizer={localizer}
          events={events}
          startAccessor="start"
          endAccessor="end"
          style={{ height: 'calc(100vh - 200px)' }} // Adjust height dynamically
          eventPropGetter={() => ({
            style: {
              backgroundColor: '#3174ad',
              color: 'white',
              borderRadius: '5px',
              border: 'none',
              display: 'block',
              padding: '10px',
              marginBottom: '10px',
              cursor: 'pointer',
            },
          })}
        />
      </div>
    </div>
  );
};

export default Calendrier;
