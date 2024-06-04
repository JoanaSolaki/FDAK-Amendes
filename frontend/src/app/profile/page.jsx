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

export default function Profile() {
  const appContext = useContext(AppContext);
  const [idTaxes, setIdTaxes] = useState("");
  const router = useRouter();

  isTokenValid();

  useEffect(() => {
    const token = localStorage.getItem("token");
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
  }, []);

  async function submitIdTaxe(event) {
    event.preventDefault();

    const token = localStorage.getItem("token");

    const idTaxes = event.target.elements.id_taxes.value;  
    
    const requestBody = JSON.stringify({ id_taxes: idTaxes });

    fetch(`http://127.0.0.1:8000/api/fines/byIdTaxes`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json/ld+json",
        Authorization: `Bearer ${token}`,
      },
      body: requestBody,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur de réseau : " + response.status);
        }
        return response.json();
      })
      .then((data) => {
        console.log(data);
      })
      .catch((error) => {
        console.error("Erreur lors de la requête :", error);
      });
    }

  return (
    <main>
      <h1 className={`${audiowide.className}`}>
        {(!appContext.userData && "Your profile") ||
          (appContext.userData && "Profile of " + appContext.userData.name)}
      </h1>

      <h2 className={`${audiowide.className}`}>Informations personnelles</h2>
      <section className="profile">
        <form method="post" className="profileForm">
          <Input label="Adresse mail" name="email" type="email"></Input>
          <Input label="Nom" name="name" type="text"></Input>
          <Input label="Prénom" name="surname" type="text"></Input>
          <Input label="Adresse postale" name="adress" type="text"></Input>
          <Input label="Téléphone" name="phone" type="phone"></Input>
          <Button text={"Enregistrer et modifier"} type="submit"></Button>
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
        <FinePaids/>
      </section>
    </main>
  );
}
