import './bootstrap.js';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// enable the interactive UI components from Flowbite
import 'flowbite';

import { createIcons, icons } from 'lucide';
import {Routing} from "../public/bundles/fosjsrouting/js/router";

// Initialize Lucide
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// TODO: Faire la méthode de vérification de la disponibilité d'un code de profil en appelant la route check_profile_code_availability
async function checkCodeProfile(code) {
    let url = Routing.generate('check_profile_code_availability');
    let data = {
        profileCode: code
    }
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });

        const result = await response.json();

        return result.is_available;

    } catch (error) {
        console.error("Erreur lors de la vérification du code de profil : ", error);
        return false;
    }
}

const input = document.getElementById('profile-code-input');
const submitButton = document.getElementById('submit-button');

input.addEventListener('input', async () => {
    const code = input.value;

    const isValid = code.length >= 4 && code.length<255;
    const isAvailable = await checkCodeProfile(code);

    if (isValid && isAvailable) {
        input.classList.remove('border-red-500');
        submitButton.disabled = false;
    } else {
        input.classList.add('border-red-500');
        submitButton.disabled = true;
    }
});

/**
 * La route doit être appeler en POST et demande un paramètre profileCode
 * Voici ce que la route retourne en JSON :
 * {
 *     "is_available": true|false
 * }
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');