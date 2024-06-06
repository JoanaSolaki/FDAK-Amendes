import { jwtDecode } from 'jwt-decode';
import { useRouter } from "next/navigation";
import { AppContext } from '@/app/AppContext';
import { useContext } from 'react';

export function isTokenValid(route) {
    const appContext = useContext(AppContext)
    const router = useRouter();

    const token = localStorage.getItem("token");

    if (token == null) {
        router.push("/connexion");
    }

    if (token != null) {
        try {
            // Décodez le token et set le temps
            const decodedToken = jwtDecode(token);
            const currentTime = Date.now() / 1000;

            // Vérifiez si le token n'a pas expiré
            if (decodedToken.exp > currentTime) {
            // Redirigez l'utilisateur vers /profile
            if (route === "/sinscrire" || route === "/connexion" || route === "/" || route === "/paidment:*") {
                router.push("/profile");
            }
            } else {
                // Si le token a expirer
                localStorage.removeItem("token");
                appContext.setUserData(null);
                router.push("/connexion");
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            const messageError = "Erreur lors de la requête : " + error;
            appContext.setErrorMessage(messageError);
            router.push("/connexion");
        }
    }
}
