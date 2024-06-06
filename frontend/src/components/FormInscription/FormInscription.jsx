import { InputText } from 'primereact/inputtext';
import './formInscription.css';
import Button from '@/components/Button/Button';

export default function FormInscription({ onSubmit }) {
    return (
        <form method='post' onSubmit={onSubmit} className='inscription'>
            <div>
                <label htmlFor='email'>Adresse mail*</label>
                <InputText keyfilter="email" type="email" name="email" minLength="10" maxLength="100" required />
            </div>
            <div>
                <label htmlFor='password'>Mot de passe*</label>
                <InputText name="password" type="password" minLength="6" maxLength="255" required />
            </div>
            <div>
                <label htmlFor='name'>Nom*</label>
                <InputText name="name" minLength="3" maxLength="100" required />
            </div>
            <div>
                <label htmlFor='surname'>Prénom*</label>
                <InputText name="surname" minLength="3" maxLength="100" required />
            </div>
            <div>
                <label htmlFor='adress'>Adresse postale*</label>
                <InputText name="adress" minLength="10" maxLength="255" required />
            </div>
            <div>
                <label htmlFor='phone'>Téléphone*</label>
                <InputText keyfilter="num" type='phone' name="phone" minLength="10" maxLength="14" required />
            </div>
            <Button text={"S'inscrire"} type="submit"></Button>
        </form>
    );
}