import './bootstrap.js';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// enable the interactive UI components from Flowbite
import 'flowbite';

import { createIcons, icons } from 'lucide';

// Initialize Lucide
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// TODO: Faire la méthode de vérification de la disponibilité d'un code de profil en appelant la route check_profile_code_availability
/**
 * La route doit être appeler en POST et demande un paramètre profileCode
 * Voici ce que la route retourne en JSON :
 * {
 *     "is_available": true|false
 * }
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');