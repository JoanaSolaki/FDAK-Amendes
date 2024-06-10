"use client";

import { audiowide } from "../layout";
import { AppContext } from "@/app/AppContext";
import { isTokenValid } from "../tokenVerify";
import Input from "@/components/Input/Input";
import Button from "@/components/Button/Button";
import FinePaids from "@/components/FinePaids/FinePaids";
import { jwtDecode } from "jwt-decode";
import { useContext, useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { Message } from 'primereact/message';

export default function Profile() {
  const appContext = useContext(AppContext);
  const [idTaxes, setIdTaxes] = useState("");
  const [loading, setloading] = useState(false);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem("token");
    appContext.setErrorMessage(null)
    appContext.setSucessMessage(null)
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
  }, []);

  isTokenValid();

  const validateIdTaxes = (idTaxes) => {
    const code = idTaxes;

    const currentDate = new Date;
    const currentYear = currentDate.getFullYear()

    const letter1 = code.charAt(0);
    const letter2 = code.charAt(1);
    const year = code.slice(2, 6);
    const parts = code.split('_');
    const num1 = parseInt(parts[1], 10);
    const num2 = parseInt(parts[2], 10);

    const compareValue = letter1.localeCompare(letter2)
    if (compareValue === 1) {
      const error = "La première lettre n'est pas avant la seconde dans l'alphabet."
      appContext.setErrorMessage(error);
      console.log(error);
      return false
    }

    if (year != currentYear) {
      const error = "L'année n'est pas la bonne."
      appContext.setErrorMessage(error);
      console.log(error);
      return false
    }
    
    const compareTotalNumber = num1 + num2
    if (compareTotalNumber != 100) {
      const error = "Le total des deux derniers chiffres ne fait pas 100."
      appContext.setErrorMessage(error);
      console.log(error);
      return false
    }
  
    return true
  };

  async function submitIdTaxe(event){
    event.preventDefault();

    const token = localStorage.getItem("token");

    const idTaxes = event.target.elements.id_taxes.value;

    if (validateIdTaxes(idTaxes) === false) {
      // appContext.setErrorMessage("ID de taxe invalide. Veuillez vérifier et réessayer.");
      return;
    }

    if (validateIdTaxes(idTaxes) === true) {
      console.log("Le code est valide !");
      }

    fetch(`http://127.0.0.1:8000/api/fines/IdTaxes/${idTaxes}`, {
      method: "GET",
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur de réseau : " + response.status);
        }
        setIdTaxes(idTaxes);
        return response.json();
      })
      .then((data) => {
        console.log(data);
        router.push("/paidment:" + data.id)
      })
      .catch((error) => {
        console.error("Erreur lors de la requête : ", error);
        appContext.setErrorMessage("La requête n'as pas abouti ou n'as pas été trouvée.");
        setIdTaxes(null);
      });
    }

  return (
    loading ? (
      <main>
      <h1 className={`${audiowide.className}`}>
        {(!appContext.userData && "Your profile") ||
          (appContext.userData && "Profile of " + appContext.userData.name)}
      </h1>
      {appContext.errorMessage != null && (
        <Message severity="error" text={"Une erreur est survenue : " + appContext.errorMessage} />)}
      {appContext.sucessMessage != null && (
        <Message severity="sucess" text={appContext.sucessMessage} />)}
      <h2 className={`${audiowide.className}`}>Informations personnelles</h2>
      <section className="profile">
        <form method="post" className="profileForm">
            <Input label="Adresse mail" name="email" type="email" value={appContext.userData.email} />
            <Input label="Nom" name="name" type="text" value={appContext.userData.name} />
            <Input label="Prénom" name="surname" type="text" value={appContext.userData.surname} />
            <Input label="Adresse postale" name="adress" type="text" value={appContext.userData.adress} />
            <Input label="Téléphone" name="phone" type="phone" value={appContext.userData.phone} />
            <Button text={"Enregistrer et modifier"} type="submit" />
        </form>
      </section>

      <h2 className={`${audiowide.className}`}>Payer une taxe</h2>
      <section className="profile">
        <form className="taxesForm" onSubmit={submitIdTaxe}>
          <Input label="ID de la demande de règlement" name="id_taxes" type="text" id="id_taxes"/>
          <Button text={"Soumettre la demande"} type="submit"/>
        </form>
      </section>

      <h2 className={`${audiowide.className}`}>Règlements enregistrés</h2>
      <section className="profile">
        <FinePaids paidments={appContext.userData.paidments}/>
      </section>
    </main>
    ) : (<p>Loading</p>)
  );
}
