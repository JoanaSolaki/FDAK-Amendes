"use client";

import { audiowide } from '../layout';
import FormInscription from "@/components/FormInscription/FormInscription";
import Intro from "@/components/Intro/Intro";
import { AppContext } from '@/app/AppContext';
import { isTokenValid } from "../tokenVerify";
import { useContext } from 'react';
import { useRouter } from 'next/navigation';
import { Message } from 'primereact/message';

export default function Sinscrire() {
  const appContext = useContext(AppContext)
  const router = useRouter();

  isTokenValid("/sinscrire");

  async function onSubmit(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const formObject = Object.fromEntries(formData.entries());

    fetch('http://127.0.0.1:8000/api/users', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/ld+json'
      },
      body: JSON.stringify(formObject)
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur de réseau :" + response.status);
      }
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        response.json();
      }
    })
    .then((data) => {
      console.log(data);
      appContext.setSucessMessage("Votre compte à bien été créer, veuillez vous connecter.");
      router.push('/connexion');
    })
    .catch((error) => {
      console.error("Erreur lors de la requête :", error);
      const messageError = "Erreur lors de la requête : " + error;
      appContext.setErrorMessage(messageError);
    });
}

return (
    <main>
      <Intro></Intro>
      {appContext.errorMessage != null && (
        <Message severity="error" text={"Une erreur est survenue" + appContext.errorMessage} />
      )}
      <h1 className={`${audiowide.className} text-center`}>S'inscrire</h1>
      <FormInscription onSubmit={onSubmit}></FormInscription>
    </main>
);
}
