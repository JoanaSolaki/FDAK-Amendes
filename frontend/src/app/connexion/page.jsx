"use client";

import { audiowide } from '../layout';
import FormConnexion from "@/components/FormConnexion/FormConnexion";
import { isTokenValid } from "../tokenVerify";
import Intro from "@/components/Intro/Intro";
import { AppContext } from '@/app/AppContext';
import { useContext } from 'react';
import { useRouter } from 'next/navigation';

export default function Connexion() {
    const router = useRouter();
    const appContext = useContext(AppContext)

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
          console.log(data);
          localStorage.setItem('token', data.token)
          appContext.setToken(data.token)
          router.push("/profile");
        })
        .catch((error) => {
            console.error("Erreur lors de la requête :", error);
        });
    }

    return (
        <main>
          <Intro></Intro>
          <h1 className={`${audiowide.className} text-center`}>Connexion</h1>
          <FormConnexion onSubmit={onSubmit}></FormConnexion>
        </main>
    );
}
