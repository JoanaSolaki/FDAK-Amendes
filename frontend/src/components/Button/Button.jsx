import './button.css'
import { audiowide } from '../../app/layout'

export default function Button(props) {
    return (
        <button className={audiowide.className}>{props.text}</button>
    )
}