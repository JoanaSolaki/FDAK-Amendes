import { InputText } from 'primereact/inputtext';
import './formConnexion.css';
import Button from '@/components/Button/Button';

export default function FormConnexion({ onSubmit }) {
    return (
        <form method='post' onSubmit={onSubmit} className='connexion'>
            <div>
                <label htmlFor='email'>Adresse mail*</label>
                <InputText keyfilter="email" type="email" name="username" minLength="10" maxLength="100" required />
            </div>
            <div>
                <label htmlFor='password'>Mot de passe*</label>
                <InputText name="password" type="password" minLength="6" maxLength="255" required />
            </div>
            <Button text={"Se connecter"} type="submit" id="connexion_button"></Button>
        </form>
    );
}