"use client"

import { InputText } from 'primereact/inputtext';
import './finePaids.css';
import { useEffect, useState } from 'react';

export default function FinePaids(props) {
    const [payments, setPayments] = useState([]);

    useEffect(() => {
        const token = localStorage.getItem('token');
        const headers = {
          'Authorization': `Bearer ${token}`,
        };
      
        const fetchPayments = async () => {
          try {
            const paymentDetails = await Promise.all(
              props.paidments.map(async (paymentId) => {
                const response = await fetch(`http://127.0.0.1:8000${paymentId}`, { headers: headers });
                if (!response.ok) {
                  throw new Error('Failed to fetch payment details');
                }
                return response.json(); // Renvoie directement les données JSON
              })
            );
      
            const fineIds = paymentDetails.map((payment) => payment.fine); // Récupère les IDs de fines

            const fineDetails = await Promise.all(
              fineIds.map(async (fineId) => {
                const fineResponse = await fetch(`http://127.0.0.1:8000${fineId}`, { headers: headers });
                if (!fineResponse.ok) {
                  throw new Error('Failed to fetch fine details');
                }
                return fineResponse.json(); // Renvoie directement les données JSON
              })
            );
      
            const combinedDetails = paymentDetails.map((payment, index) => ({
              ...payment,
              fine: fineDetails[index]
            }));
      
            setPayments(combinedDetails);
          } catch (error) {
            console.log(error.message);
          }
        };
        fetchPayments();
      }, [props.paidments]);


    return (
        <>
        <div className='finePaid'>
            <p>KW2024_22_78</p>
            <p>Non-respect des consignes de sécurité lors des attaques (ceci est le test)</p>
            <p>300€</p>
        </div>
        {payments.map((payment) => (
            <div className='finePaid' key={payment.id}>
            <p>{payment.fine.idTaxes}</p> {/* ID de l'amende */}
            <p>{payment.fine.reason}</p> {/* Description de l'amende */}
            <p>{payment.fine.amount}€</p> {/* Montant de l'amende */}
            </div>
        ))}
        </>
    );
}