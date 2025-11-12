document.addEventListener('DOMContentLoaded', () => {

    const objetsContainer = document.getElementById('objets-container');
    const optionsContainer = document.getElementById('options-reponse');
    const feedbackElement = document.getElementById('feedback');
    const titreElement = document.querySelector('h1');

    let targetNumber = 0;
    const apiUrl = 'api/game.php';

    // --- MODIFICATION : Liste des animaux basée sur des fichiers externes ---
    const animaux = [
        { nom: 'chien', fichier: 'chien.svg' },
        { nom: 'poules', fichier: 'poule.svg' },
        { nom: 'cochons', fichier: 'cochon.svg' },
        { nom: 'vaches', fichier: 'vache.svg' },
        { nom: 'cheval', fichier: 'cheval.svg' },
        { nom: 'chevre', fichier: 'chevre.svg' },
        { nom: 'canards', fichier: 'canard.svg' }
        // Ajoutez ici autant d'animaux que vous avez de fichiers SVG
    ];

    async function demarrerNouvellePartie() {
        // ... (partie AJAX inchangée)
        feedbackElement.textContent = 'Chargement...';
        optionsContainer.innerHTML = '';

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.success) {
                targetNumber = data.target;
                const animalChoisi = animaux[Math.floor(Math.random() * animaux.length)];
                
                titreElement.textContent = `Combien de ${animalChoisi.nom} vois-tu ?`;

                feedbackElement.textContent = '';
                afficherAnimaux(targetNumber, animalChoisi.fichier); // On passe le nom du fichier
                genererOptions();
            }
        } catch (error) {
            // ...
        }
    }
    
    // --- MODIFICATION : La fonction crée des balises <img> ---
    function afficherAnimaux(nombre, nomFichier) {
        objetsContainer.innerHTML = ''; 
        for (let i = 0; i < nombre; i++) {
            const img = document.createElement('img');
            img.src = `img/${nomFichier}`; // Construit le chemin vers l'image
            img.alt = nomFichier.split('.')[0]; // Pour l'accessibilité
            img.classList.add('objet');
            objetsContainer.appendChild(img);
        }
    }

    function genererOptions() {
        // ... (fonction inchangée)
        optionsContainer.innerHTML = '';
        for (let i = 1; i <= 10; i++) {
            const jeton = document.createElement('div');
            jeton.classList.add('jeton');
            jeton.textContent = i;
            jeton.dataset.valeur = i; 
            jeton.addEventListener('click', handleResponseClick);
            optionsContainer.appendChild(jeton);
        }
    }

    function handleResponseClick(event) {
        // ... (fonction inchangée)
        const clickedJeton = event.currentTarget;
        const selectedValue = parseInt(clickedJeton.dataset.valeur, 10);

        document.querySelectorAll('.jeton').forEach(j => j.classList.remove('jeton-selectionne'));
        clickedJeton.classList.add('jeton-selectionne');

        if (selectedValue === targetNumber) {
            feedbackElement.textContent = 'Bravo ! 🎉';
            feedbackElement.style.color = '#2ecc71';
            optionsContainer.style.pointerEvents = 'none';

            setTimeout(() => {
                optionsContainer.style.pointerEvents = 'auto';
                demarrerNouvellePartie();
            }, 2000);
        } else {
            feedbackElement.textContent = 'Essaie encore ! 🤔';
            feedbackElement.style.color = '#e74c3c';
        }
    }
    
    demarrerNouvellePartie();
});
