import { jwtDecode } from 'jwt-decode';
import { useRouter } from "next/navigation";
import { AppContext } from '@/app/AppContext';
import { useContext, useEffect } from 'react';

export function isTokenValid() {
    const appContext = useContext(AppContext)
    const router = useRouter();

    const token = localStorage.getItem("token");

    if (token == null) {
        router.push("/connexion");
    }

    if (token != null) {
        try {
            // Décodez le token pour vérifier sa validité
            const decodedToken = jwtDecode(token);
            const currentTime = Date.now() / 1000;

            // Vérifiez si le token n'a pas expiré
            if (decodedToken.exp > currentTime) {
            // Redirigez l'utilisateur vers /profile
            router.push("/profile");
            } else {
            // Le token a expiré, supprimez-le du local storage
            localStorage.removeItem("token");
            appContext.setUserData(null);
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            const messageError = "Erreur lors de la requête : " + error;
            appContext.setErrorMessage(messageError);
            router.push("/connexion");
        }
    }
}
