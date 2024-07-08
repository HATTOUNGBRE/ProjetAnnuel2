import React, { useEffect, useState } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import { createPopper } from '@popperjs/core';

const Calendar = ({ property }) => {
    const [events, setEvents] = useState([]);

    useEffect(() => {
        const fetchReservations = async () => {
            try {
                const response = await fetch(`http://localhost:8000/api/properties/${property.id}/reservations`);
                const data = await response.json();
                console.log('Fetched reservations:', data);

                const reservations = data.map(reservation => ({
                    title: `Réservation - ${reservation.guestNb} personnes`,
                    start: reservation.dateArrivee,
                    end: reservation.dateDepart,
                    color: 'gray',
                    textColor: 'white',
                    extendedProps: {
                        voyageurId: reservation.voyageur.id, // Assuming reservation object has voyageur.id
                    }
                }));

                console.log('Transformed events:', reservations);
                setEvents(reservations);
            } catch (error) {
                console.error('Error fetching reservations:', error);
            }
        };

        if (property) {
            fetchReservations();
        }
    }, [property]);

    const handleEventMouseEnter = async (info) => {
        const voyageurId = info.event.extendedProps.voyageurId;
        try {
            const response = await fetch(`http://localhost:8000/api/voyageurs/${voyageurId}`);
            const voyageur = await response.json();
            console.log('Fetched voyageur details:', voyageur);

            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.innerHTML = `${voyageur.name} ${voyageur.surname} - ${info.event.extendedProps.guestNb} guests`;
            document.body.appendChild(tooltip);

            createPopper(info.el, tooltip, {
                placement: 'top',
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: [0, 8],
                        },
                    },
                ],
            });

            info.el.addEventListener('mouseleave', () => {
                tooltip.remove();
            });
        } catch (error) {
            console.error('Error fetching voyageur details:', error);
        }
    };

    return (
        <FullCalendar
            plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
            initialView="dayGridMonth"
            events={events}
            eventMouseEnter={handleEventMouseEnter}
            headerToolbar={{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            }}
        />
    );
};

export default Calendar;
