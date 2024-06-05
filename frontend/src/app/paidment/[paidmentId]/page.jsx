"use client"

import { useRouter } from 'next/navigation';
import { isTokenValid } from "../../tokenVerify";
import { useEffect, useState } from 'react';

export default function Payment(props) {
  const router = useRouter();
  const id = props.params.paidmentId;
  const [fineDetails, setFineDetails] = useState(null);

  isTokenValid();

  useEffect(() => {
    const token = localStorage.getItem("token");

    if (id) {
      fetch(`http://127.0.0.1:8000/api/fines/${id}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur de réseau : " + response.status);
          }
          return response.json();
        })
        .then((data) => {
          setFineDetails(data);
        })
        .catch((error) => {
          console.error("Erreur lors de la requête :", error);
          setLoading(false);
        });
    }
  }, [id]);

  const handlePayment = () => {
    const token = localStorage.getItem("token");

    fetch(`http://127.0.0.1:8000/api/paidment`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur de réseau : " + response.status);
        }
        return response.json();
      })
      .then((data) => {
        alert("Paiement effectué avec succès !");
        router.push("/profile");
      })
      .catch((error) => {
        console.error("Erreur lors de la requête :", error);
        alert("Une erreur s'est produite lors du paiement.");
      });
  };

  if (!fineDetails) {
    return <div>Aucune information sur l'amende trouvée.</div>;
  }

  return (
    <div>
      <h1>Détails de l'amende</h1>
      <p>ID de taxe : {fineDetails.id_taxes}</p>
      <p>Description : {fineDetails.description}</p>
      <p>Montant : {fineDetails.amount}</p>
      <button onClick={handlePayment}>Payer</button>
    </div>
  );
}