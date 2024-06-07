"use client";

import './navbar.css';
import Link from "next/link";
import { useContext, useEffect, useState } from 'react';
import { AppContext } from '@/app/AppContext';
import { useRouter } from 'next/navigation';
import { jwtDecode } from 'jwt-decode';

export default function Navbar() {
  const appContext = useContext(AppContext);

  useEffect(() => {
    const token = localStorage.getItem("token");
    appContext.setToken(token);
  }, [appContext]);

  function logout() {
    localStorage.removeItem("token");
    appContext.setToken(null);
    appContext.setUserData(null);
    window.location.href = "/connexion";
  }

  return (
    <nav className='navbar shift'>
      <ul>
        <li>
          <img src="FDAK_logo.png" alt="FDAK logo" />
        </li>
        <li>
          <Link href="/" className='brand'>FDAK Amendes</Link>
        </li>
      </ul>
      {(!appContext.token) &&
        <ul>
          <li>
            <Link href="/sinscrire">S'inscrire</Link>
          </li>
          <li>
            <Link href="/connexion">Connexion</Link>
          </li>
        </ul>}
      {(appContext.token) &&
        <ul>
          <li>
            <Link href="/profile">{appContext.userData ? appContext.userData.name : "Username" }</Link>
          </li>
          <li>
            <a onClick={logout}>Déconnexion</a>
          </li>
        </ul>}
    </nav>
  );
}
