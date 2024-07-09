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

                const reservations = data.map(reservation => {
                    let color = '#A5BE00'; // Default color for accepted reservations
                    if (reservation.status === 'Annulée') {
                        color = 'gray'; // Color for canceled reservations
                    } else if (reservation.status === 'En attente') {
                        color = 'orange'; // Color for pending reservations
                    }

                    return {
                        title: `Réservation - ${reservation.guestNb} personnes`,
                        start: reservation.dateArrivee,
                        end: reservation.dateDepart,
                        color: color,
                        textColor: 'white',
                        extendedProps: {
                            name: `${reservation.voyageurName} ${reservation.voyageurSurname}`,
                            guestNb: reservation.guestNb,
                            status: reservation.status // Adding the status to the extendedProps
                        }
                    };
                });

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

    const handleEventMouseEnter = (info) => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.innerHTML = `${info.event.extendedProps.name} - ${info.event.extendedProps.guestNb} guests - Status: ${info.event.extendedProps.status}`;
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
