"use client"

import './navbar.css'
import Link from "next/link";
import { useContext } from 'react';
// import { AppContext } from '@/app/AppContext';

export default function Navbar() {
//   const appContext = useContext(AppContext)

//   const handleSearchChange = (event) => {
//     setTimeout(() => { appContext.setSearch(event.target.value) }, 1400)
//   };

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
      <ul>
        <li>
          <Link href="/sinscrire">S'inscrire</Link>
        </li>
        <li>
          <Link href="/connexion">Connexion</Link>
        </li>
      </ul>
    </nav>
  )
}