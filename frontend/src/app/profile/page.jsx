"use client";

import { audiowide } from '../layout';
import { AppContext } from '@/app/AppContext';
import { isTokenValid  } from '../tokenVerify';
import Input from "@/components/Input/Input";
import Button from '@/components/Button/Button';
import FinePaids from '@/components/FinePaids/FinePaids';
import { jwtDecode } from 'jwt-decode';
import { useEffect, useState } from 'react';

export default function Profile() {
    // const appContext = useContext(AppContext)
    const [userData, setUserData] = useState(null);

    isTokenValid()

    const jwt = localStorage.getItem('token');
    const email = jwtDecode(jwt);
    console.log(email);
    const userId = email.username;
    console.log(userId);

    useEffect(() => {
      const jwt = localStorage.getItem('token');
      if (jwt) {
          const decodedToken = jwtDecode(jwt);
          const email = decodedToken.email;

          fetch(`http://127.0.0.1:8000/api/users/${email}`, {
              method: 'GET',
              headers: {
                  'Authorization': `Bearer ${jwt}`
              }
          })
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok');
              }
              return response.json();
          })
          .then(data => {
              setUserData(data);
          })
          .catch(error => {
              console.error('There was a problem with the fetch operation:', error);
          });
      }
  }, []);

  console.log("userdata : " + userData);

    return (
        <main>
            <h1 className={`${audiowide.className}`}>Profile of {userData}</h1>

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
