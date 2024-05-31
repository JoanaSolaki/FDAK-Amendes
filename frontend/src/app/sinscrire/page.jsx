"use client";

import { audiowide } from '../layout';
import FormInscription from "@/components/FormInscription/FormInscription";

export default function Sinscrire() {

    async function onSubmit(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        fetch('http://127.0.0.1:8000/api/users', {
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
          console.log(data);
        })
        .catch((error) => {
          console.error("Erreur lors de la requête :", error);
        });

        window.location.href = "/connexion";
    }

    return (
        <main>
            <h1 className={`${audiowide.className} text-center`}>S'inscrire</h1>
            <FormInscription onSubmit={onSubmit}></FormInscription>
        </main>
    );
}