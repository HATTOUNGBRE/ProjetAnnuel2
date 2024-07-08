import React, { useEffect, useState } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';

const Calendar = ({ property }) => {
    const [events, setEvents] = useState([]);

    useEffect(() => {
        const fetchReservations = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/properties/${property.id}/reservations`);
                const data = await response.json();
                console.log('Fetched reservations:', data); // Log fetched data

                const reservations = data.map(reservation => ({
                    title: `Réservation - ${reservation.guestNb} personnes`,
                    start: new Date(reservation.dateArrivee), // Ensure correct date format
                    end: new Date(reservation.dateDepart), // Ensure correct date format
                    color: 'gray', // Use gray color to indicate the reservation period
                    textColor: 'white'
                }));

                console.log('Transformed events:', reservations); // Log transformed events
                setEvents(reservations);
            } catch (error) {
                console.error('Error fetching reservations:', error);
            }
        };

        if (property) {
            fetchReservations();
        }
    }, [property]);

    return (
        <FullCalendar
            plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
            initialView="dayGridMonth"
            events={events}
            headerToolbar={{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            }}
        />
    );
};

export default Calendar;
