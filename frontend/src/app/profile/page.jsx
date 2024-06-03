"use client";

import { audiowide } from '../layout';
import { AppContext } from '@/app/AppContext';
import { isTokenValid  } from '../tokenVerify';
import Input from "@/components/Input/Input";
import Button from '@/components/Button/Button';
import FinePaids from '@/components/FinePaids/FinePaids';
import { jwtDecode } from 'jwt-decode';

export default function Profile() {
    // const appContext = useContext(AppContext)

    isTokenValid()

    const jwt = localStorage.getItem('token');
    const email = jwtDecode(jwt);

    console.log(email);

    const userId = email.username;
    
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
          const messageError = "Erreur lors de la requête : " + error;
          appContext.setMessage(messageError);
        });
    
        window.location.href = "/profile";
    }    
    
console.log(userId);
    return (
        <main>
            <h1 className={`${audiowide.className}`}>Profile</h1>

            <h2 className={`${audiowide.className}`}>Informations personnelles</h2>
            <section className='profile'>
                <form method="post" className='profileForm'>
                    <Input label="Adresse mail" name="email" type="email"></Input>
                    <Input label="Nom" name="name" type="text"></Input>
                    <Input label="Prénom" name="surname" type="text"></Input>
                    <Input label="Adresse postale" name="adress" type="text"></Input>
                    <Input label="Téléphone" name="phone" type="phone"></Input>
                    <Button text={"Enregistrer et modifier"} type="submit"></Button>
                </form>
            </section>

            <h2 className={`${audiowide.className}`}>Payer une taxe</h2>
            <section className='profile'>
                <form method="post">
                    <Input label="ID de la demande de règlement" name="id_taxes" type="text"></Input>
                    <Button text={"Soumettre la demande"} type="submit"></Button>
                </form>
            </section>

            <h2 className={`${audiowide.className}`}>Règlements enregistrés</h2>
            <section className='profile'>
                <FinePaids></FinePaids>
            </section>
        </main>
    );
}
