import { InputText } from 'primereact/inputtext';
import './input.css';

export default function Input(props) {
    return (
        <div>
            <label htmlFor={props.name}>{props.label}*</label>
            <InputText type={props.type} name={props.name} minLength={props.minLength} maxLength={props.maxLength} value={props.value} required />
        </div>
    );
}