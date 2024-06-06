"use client";

import { audiowide } from "../layout";
import { useRouter } from 'next/navigation';
import { AppContext } from "@/app/AppContext";
import { isTokenValid } from "../tokenVerify";
import { useContext, useEffect, useState } from 'react';
import { jwtDecode } from "jwt-decode";
import Input from "@/components/Input/Input";
import Button from "@/components/Button/Button";
import { Calendar } from 'primereact/calendar';

export default function Payment(props) {
  const router = useRouter();
  const [loading, setloading] = useState(false);
  const appContext = useContext(AppContext);

  const [fineDetails, setFineDetails] = useState(null);

  isTokenValid();

  useEffect(() => {
    const token = localStorage.getItem("token");

    if (props.params && props.params.paidmentId) {
      
      const actualId = props.params.paidmentId.replace("paidment%3A", "");

      fetch(`http://127.0.0.1:8000/api/fines/${actualId}`, {
        method: "GET",
        headers: {
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
        });
    }

    if (token) {
      const decodedToken = jwtDecode(token);
      const idUser = decodedToken.user.id;

      fetch(`http://127.0.0.1:8000/api/users/${idUser}`, {
        method: "GET",
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          appContext.setUserData(data);
          setloading(true);
        })
        .catch((error) => {
          console.error("There was a problem with the fetch operation:", error);
        });
    }
  }, [props]);

  if (!fineDetails) {
    return <div>Aucune information sur l'amende trouvée.</div>;
  }

  // Config Calendar Prime React
  let today = new Date();
  let month = today.getMonth();
  let year = today.getFullYear();
  let prevMonth = month === 0 ? 11 : month - 1;
  let prevYear = prevMonth === 11 ? year - 1 : year;
  let nextMonth = month === 11 ? 0 : month + 1;
  let nextYear = nextMonth === 0 ? year + 1 : year;

  const [date, setDate] = useState(null);

  let minDate = new Date();

  minDate.setMonth(prevMonth);
  minDate.setFullYear(prevYear);

  let maxDate = new Date();

  maxDate.setMonth(nextMonth);
  maxDate.setFullYear(nextYear);

// CARD_NUMBER
  const isValidCardNumber = (cardNumber) => {
    let sum = 0;
    let shouldDouble = false;
  
    for (let i = cardNumber.length - 1; i >= 0; i--) {
      let digit = parseInt(cardNumber.charAt(i));
  
      if (shouldDouble) {
        digit *= 2;
        if (digit > 9) digit -= 9;
      }
  
      sum += digit;
      shouldDouble = !shouldDouble;
    }

    return sum % 10 === 0;
  };

  const handlePayment = () => {
    const token = localStorage.getItem("token");

    isValidCardNumber();
    
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

  return (
    loading ? (
    <main>
      <h1 className={`${audiowide.className}`}>Régler l'amende</h1>

      <h2 className={`${audiowide.className} px-10 py-5`}>
        <p className="text-base leading-10">Infraction : <strong className="font-black">Refus d'obtempérer face à un membre des Forces de Défense Anti-Kaiju.</strong><br/>
        Somme à régler : <strong className="font-black">450€</strong></p></h2>
      <section className="profile">
        <form onSubmit={handlePayment} className="paidmentForm">
          <div>
            <Input label="Adresse mail" name="email" type="email" value={appContext.userData.email} />
            <Input label="Nom" name="name" type="text" value={appContext.userData.name} />
            <Input label="Prénom" name="surname" type="text" value={appContext.userData.surname} />
            <Input label="Adresse postale" name="adress" type="text" value={appContext.userData.adress} />
            <Input label="Téléphone" name="phone" type="phone" value={appContext.userData.phone} />
          </div>
          <div>
            <Input label="N° de carte bancaire" name="card" minLength="12" maxLength="16"></Input>
            <Input label="Cryptogramme" name="crypto" type="number" minLength="3" maxLength="4"></Input>
            <div>
              <label htmlFor="exp_date">Date d’expiration*</label>
              <Calendar name="exp_date" type="date" value={date} onChange={(e) => setDate(e.value)} minDate={minDate} maxDate={maxDate} readOnlyInput />
            </div>
          </div>
          <Button text={"Valider et payer"} type="submit"></Button>
        </form>
      </section>
    </main>)
    : (<p>Loading</p>)
  );
}