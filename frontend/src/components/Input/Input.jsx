import { InputText } from 'primereact/inputtext';
import './input.css';

export default function Input(props) {
    return (
        <div>
            <label htmlFor={props.name}>{props.label}*</label>
            <InputText type={props.name} name={props.name} minLength="10" maxLength="100" value={props.value} required />
        </div>
    );
}