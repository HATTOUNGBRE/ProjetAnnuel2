import React, { useState, useEffect } from 'react';
import { PDFDownloadLink, Document, Page, Text, View, StyleSheet } from '@react-pdf/renderer';

const Docs = () => {
    const [payments, setPayments] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPayments = async () => {
            try {
                const response = await fetch('http://localhost:8000/api/payments');
                const data = await response.json();
                setPayments(data);
                console.log(data);
            } catch (err) {
                setError('Failed to fetch payments');
            } finally {
                setLoading(false);
            }
        };

        fetchPayments();
    }, []);

    if (loading) {
        return <div className="flex items-center justify-center h-screen">Loading...</div>;
    }

    if (error) {
        return <div className="flex items-center justify-center h-screen text-red-500">{error}</div>;
    }

    const styles = StyleSheet.create({
        page: { padding: 30 },
        section: { marginBottom: 10 },
        title: { fontSize: 24, marginBottom: 20 },
        text: { fontSize: 14 },
        reservationNumber: { fontSize: 16, marginBottom: 10, fontWeight: 'bold' },
    });

    const PaymentDocument = ({ payments }) => (
        <Document>
            <Page style={styles.page}>
                <Text style={styles.title}>Documents et Factures</Text>
                {payments.map((payment, index) => (
                    <View key={index} style={styles.section}>
                        <Text style={styles.reservationNumber}>Numéro de Réservation: {payment.reservation.reservationNumber}</Text>
                        <Text style={styles.text}>Date: {new Date(payment.date).toLocaleDateString()}</Text>
                        <Text style={styles.text}>Montant: {payment.amount} €</Text>
                        <Text style={styles.text}>Méthode: {payment.method}</Text>
                        <Text style={styles.text}>Nom: {payment.firstName} {payment.lastName}</Text>
                        <Text style={styles.text}>Carte (4 derniers chiffres): {payment.cardLast4}</Text>
                        <Text style={styles.text}>Date d'arrivée: {new Date(payment.reservation.dateArrivee).toLocaleDateString()}</Text>
                        <Text style={styles.text}>Date de départ: {new Date(payment.reservation.dateDepart).toLocaleDateString()}</Text>
                        <Text style={styles.text}>Nombre de personnes: {payment.reservation.guestNb}</Text>
                        <Text style={styles.text}>Prix total de la réservation: {payment.reservation.totalPrice} €</Text>
                    </View>
                ))}
            </Page>
        </Document>
    );

    const getFileName = () => {
        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        const yyyy = today.getFullYear();

        return `facture_du_${dd}-${mm}-${yyyy}.pdf`;
    };

    return (
        <div className="container mx-auto p-6 bg-white rounded-lg shadow-md">
            <h1 className="text-2xl font-semibold text-pcs-400 mb-4">Documents et Factures</h1>
            <table className="min-w-full bg-white">
                <thead>
                    <tr>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Numéro de Réservation</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Date</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Montant</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Méthode</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Nom</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Carte</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Location de l'appartement</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Date d'arrivée</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Date de départ</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Nombre de personnes</th>
                        <th className="py-2 text-center px-4 bg-gray-200 text-gray-600 font-bold">Prix total</th>
                    </tr>
                </thead>
                <tbody>
                    {payments.map((payment, index) => (
                        <tr key={index} className="border-b">
                            <td className="py-2 text-center px-4 font-bold">{payment.reservation.reservationNumber}</td>
                            <td className="py-2 text-center px-4">{new Date(payment.date).toLocaleDateString()}</td>
                            <td className="py-2 text-center px-4">{payment.amount} €</td>
                            <td className="py-2 text-center px-4">{payment.method}</td>
                            <td className="py-2 text-center px-4">{payment.firstName} {payment.lastName}</td>
                            <td className="py-2 text-center px-4">{payment.cardLast4}</td>
                            <td className="py-2 text-center px-4">{payment.reservation.property.name}</td>
                            <td className="py-2 text-center px-4">{new Date(payment.reservation.dateArrivee).toLocaleDateString()}</td>
                            <td className="py-2 text-center px-4">{new Date(payment.reservation.dateDepart).toLocaleDateString()}</td>
                            <td className="py-2 text-center px-4">{payment.reservation.guestNb}</td>
                            <td className="py-2 text-center px-4">{payment.reservation.totalPrice} €</td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <div className="mt-4">
                <PDFDownloadLink document={<PaymentDocument payments={payments} />} fileName={getFileName()}>
                    {({ loading }) => (
                        <button className="bg-pcs-300 text-white py-2 text-center px-4 rounded-lg hover:bg-pcs-400">
                            {loading ? 'Loading document...' : 'Download PDF'}
                        </button>
                    )}
                </PDFDownloadLink>
            </div>
        </div>
    );
};

export default Docs;
