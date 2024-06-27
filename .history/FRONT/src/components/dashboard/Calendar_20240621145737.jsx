import React, { useState, useEffect } from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import DisponibilityCalendar from './DisponibilityCalendar';

const localizer = momentLocalizer(moment);

const DisponibilityCalendar = () => {
    const [events, setEvents] = useState([]);

    useEffect(() => {
        // Fetch the availability events from your API
        const fetchEvents = async () => {
            try {
                const response = await fetch('http://localhost:8000/api/disponibilities');
                const data = await response.json();
                setEvents(data);
            } catch (error) {
                console.error('Error fetching events:', error);
            }
        };

        fetchEvents();
    }, []);

    return (
        <div className="p-4 bg-white rounded-lg shadow-md">
            <h2 className="text-2xl font-bold mb-4">Disponibilité du Prestataire</h2>
            <Calendar
                localizer={localizer}
                events={events}
                startAccessor="start"
                endAccessor="end"
                style={{ height: 500 }}
            />
        </div>
    );
};

export default DisponibilityCalendar;
