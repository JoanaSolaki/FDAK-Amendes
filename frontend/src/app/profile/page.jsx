"use client";

import { audiowide } from '../layout';
import { AppContext } from '@/app/AppContext';
import { isTokenValid  } from '../tokenVerify';

export default function Profile() {
    // const appContext = useContext(AppContext)

    isTokenValid()

    return (
        <main>
            <h1 className={`${audiowide.className}`}>Profile</h1>
        </main>
    );
}
