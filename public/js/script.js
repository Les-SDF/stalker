// TODO: Faire la méthode de vérification de la disponibilité d'un code de profil en appelant la route check_profile_code_availability

/**
 * La route doit être appeler en POST et demande un paramètre profileCode
 * Voici ce que la route retourne en JSON :
 * {
 *     "is_available": true|false
 * }
 */

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('profile-code-input');
    const submitButton = document.getElementById('submit-button');

    if (input && submitButton) {
        input.addEventListener('input', async () => {
            const code = input.value;

            submitButton.classList.add('opacity-50');
            submitButton.classList.remove('opacity-100');
            submitButton.disabled = true;

            const isValid = code.length >= 4 && code.length < 255 && /^[a-zA-Z0-9]+$/.test(code);
            const isAvailable = await checkCodeProfile(code);

            if (isValid && isAvailable) {
                submitButton.classList.remove('opacity-50');
                submitButton.classList.add('opacity-100');
                submitButton.disabled = false;
            }
        });
    }
});

async function checkCodeProfile(code) {
    let url = Routing.generate('check_profile_code_availability');
    let data = {
        profileCode: code
    }
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });


        if (!response.ok) {
            const errorText = await response.text();
            console.error("Erreur lors de la vérification du code de profil : ", response.status, errorText);
            return false;
        }

        const result = await response.json();
        console.log(result);
        return result.is_available;

    } catch (error) {
        console.error("Erreur lors de la vérification du code de profil : ", error);
        return false;
    }
}