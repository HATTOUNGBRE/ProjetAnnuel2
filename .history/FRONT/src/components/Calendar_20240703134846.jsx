import React, { useState, useEffect } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import '@fullcalendar/core/main.css';
import '@fullcalendar/daygrid/main.css';

const Calendar = () => {
    const [events, setEvents] = useState([]);

    useEffect(() => {
        const fetchAvailabilities = async () => {
            try {
                const response = await fetch("http://localhost:8000/api/availabilities");
                const data = await response.json();
                const formattedEvents = data.map(availability => ({
                    title: availability.available ? 'Available' : 'Not Available',
                    start: availability.startDate,
                    end: availability.endDate,
                    color: availability.available ? 'white' : 'gray',
                }));
                setEvents(formattedEvents);
            } catch (error) {
                console.error('Error fetching availabilities:', error);
            }
        };

        fetchAvailabilities();
    }, []);

    return (
        <div className="calendar-container ">
            <FullCalendar
                plugins={[dayGridPlugin]}
                initialView="dayGridMonth"
                events={events}
            />
        </div>
    );
};

export default Calendar;
