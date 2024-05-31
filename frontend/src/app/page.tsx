// 'use server'
 
import { redirect } from 'next/navigation'
import { permanentRedirect } from 'next/navigation'

export default function Home() {
  // const userConnected = localStorage.getItem("token")
  // if ( userConnected != null) {
  //   return (
  //     function myFunction() {
  //     window.location.href = "/user";
  //     }
  //   )
  // } else {
  //   return (
  //     function myFunction() {
  //     window.location.href = "/sinscrire";
  //     }
  //   )
  // }
  // redirect(`/sinscrire`)
  permanentRedirect(`/sinscrire`)
}
