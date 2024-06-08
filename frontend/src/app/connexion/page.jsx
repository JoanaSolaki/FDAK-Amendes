"use client";

import { audiowide } from '../layout';
import FormConnexion from "@/components/FormConnexion/FormConnexion";
import { isTokenValid } from "../tokenVerify";
import Intro from "@/components/Intro/Intro";
import { AppContext } from '@/app/AppContext';
import { useContext } from 'react';
import { useRouter } from 'next/navigation';
import { Message } from 'primereact/message';

export default function Connexion() {
    const router = useRouter();
    const appContext = useContext(AppContext)

    appContext.setErrorMessage(null)

    isTokenValid("/connexion");

    async function onSubmit(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        fetch('http://127.0.0.1:8000/api/login_check', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/ld+json'
          },
          body: JSON.stringify(Object.fromEntries(formData.entries()))
        })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Erreur de réseau : " + response.status);
          }
          return response.json();
        })
        .then((data) => {
          localStorage.setItem('token', data.token)
          appContext.setToken(data.token)
          router.push("/profile");
        })
        .catch((error) => {
            console.error("Erreur lors de la requête :", error);
            appContext.setErrorMessage("La requête n'as pas abouti ou n'as pas été trouvée.");
        });
    }

    return (
        <main>
          <Intro></Intro>
          {appContext.errorMessage != null && (
            <Message severity="error" text={"Une erreur est survenue : " + appContext.errorMessage} />
          )}
          {appContext.sucessMessage != null && (
            <Message severity="sucess" text={appContext.sucessMessage} />
          )}
          <h1 className={`${audiowide.className} text-center`}>Connexion</h1>
          <FormConnexion onSubmit={onSubmit} className="connexion"></FormConnexion>
        </main>
    );
}
