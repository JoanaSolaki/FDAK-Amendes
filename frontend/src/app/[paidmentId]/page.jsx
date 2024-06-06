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
        })
        .catch((error) => {
          console.error("There was a problem with the fetch operation:", error);
        });
    }
  }, [props]);

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
    <main>
      <h1 className={`${audiowide.className}`}>Régler l'amende</h1>

      <h2 className={`${audiowide.className} px-10 py-5`}>
        <p className="text-base leading-10">Infraction : <strong className="font-black">Refus d'obtempérer face à un membre des Forces de Défense Anti-Kaiju.</strong><br/>
        Somme à régler : <strong className="font-black">450€</strong></p></h2>
      <section className="profile">
        <form method="post" className="paidmentForm">
          <div>
          {appContext.userData != null ? <>
            <Input label="Adresse mail" name="email" type="email" value={appContext.userData.email} />
            <Input label="Nom" name="name" type="text" value={appContext.userData.name} />
            <Input label="Prénom" name="surname" type="text" value={appContext.userData.surname} />
            <Input label="Adresse postale" name="adress" type="text" value={appContext.userData.adress} />
            <Input label="Téléphone" name="phone" type="phone" value={appContext.userData.phone} />
            </> : 
            <>
            <Input label="Adresse mail" name="email" type="email" />
            <Input label="Nom" name="name" type="text" />
            <Input label="Prénom" name="surname" type="text" />
            <Input label="Adresse postale" name="adress" type="text" />
            <Input label="Téléphone" name="phone" type="phone" />
            </>}
          </div>
          <div>
            <Input label="N° de carte bancaire" name="card"></Input>
            <Input label="Cryptogramme" name="crypto" type="number"></Input>
            <div>
              <label htmlFor="exp_date">Date d’expiration*</label>
              <Calendar name="exp_date" type="date" value={date} onChange={(e) => setDate(e.value)} minDate={minDate} maxDate={maxDate} readOnlyInput />
            </div>
          </div>
          <Button text={"Valider et payer"} type="submit"></Button>
        </form>
      </section>
    </main>
  );
}