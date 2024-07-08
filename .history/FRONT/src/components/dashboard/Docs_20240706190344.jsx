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
            } catch (err) {
                setError('Failed to fetch payments');
            } finally {
                setLoading(false);
            }
        };

        fetchPayments();
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    const styles = StyleSheet.create({
        page: { padding: 30 },
        section: { marginBottom: 10 },
        title: { fontSize: 24, marginBottom: 20 },
        text: { fontSize: 14 }
    });

    const PaymentDocument = ({ payments }) => (
        <Document>
            <Page style={styles.page}>
                <Text style={styles.title}>Documents et Factures</Text>
                {payments.map((payment, index) => (
                    <View key={index} style={styles.section}>
                        <Text style={styles.text}>Date: {payment.date}</Text>
                        <Text style={styles.text}>Montant: {payment.amount} €</Text>
                        <Text style={styles.text}>Méthode: {payment.method}</Text>
                    </View>
                ))}
            </Page>
        </Document>
    );

    return (
        <div>
            <h1>Documents et Factures</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                    </tr>
                </thead>
                <tbody>
                    {payments.map((payment, index) => (
                        <tr key={index}>
                            <td>{payment.date}</td>
                            <td>{payment.amount} €</td>
                            <td>{payment.method}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <PDFDownloadLink document={<PaymentDocument payments={payments} />} fileName="payments.pdf">
                {({ loading }) => (loading ? 'Loading document...' : 'Download PDF')}
            </PDFDownloadLink>
        </div>
    );
};

export default Docs;
