/**
 * Charge une image et retourne une Promise.
 * @param {string} src - Le chemin de l'image.
 */
function chargerUneImage(src) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve({ src, img });
        img.onerror = (err) => reject(new Error(`Échec du chargement de ${src}: ${err}`));
        img.src = src;
    });
}

class PuzzleGame {
    
    /**
     * Le constructeur initialise l'état et récupère tous les éléments du DOM.
     */
    constructor() {
    // --- Éléments du DOM ---
        this.conteneurMiniatures = document.querySelector('.grille-miniatures');
        this.chargementEl = document.querySelector('.chargement');
        this.écranSélection = document.getElementById('écran-sélection');
        this.écranJeu = document.getElementById('écran-jeu');
        this.messageVictoire = document.getElementById('message-victoire');
        this.compteurEl = document.getElementById('compteur');
        this.zonesJeuEl = document.querySelector('.zones-jeu');
    
        // --- Canvas et Contextes ---
        this.canvasPieces = document.getElementById('canvas-pieces');
        this.ctxPieces = this.canvasPieces.getContext('2d');
        this.canvasResolution = document.getElementById('canvas-résolution');
        this.ctxResolution = this.canvasResolution.getContext('2d');
        this.canvasOverlay = document.getElementById('canvas-overlay');
        this.ctxOverlay = this.canvasOverlay.getContext('2d');
        
        // --- Boutons ---
        this.btnRetour = document.getElementById('btn-retour');
        this.btnMélanger = document.getElementById('btn-mélanger');
    
        // --- État du jeu ---
        this.catalogueImages = []; // NOUVEAU : Stockera [ {thumb, original}, ... ]
        this.imageActive = null;   // (inchangé)
        this.pieces = [];
        this.piècesPlacées = 0;
        this.pieceWidth = 0;
        this.pieceHeight = 0;
        this.positionsGrillePieces = []; 
    
        // --- État de l'interaction ---
        this.indexPieceSelectionnee = -1;
        this.pieceSelectionneeParTap = null;
        this.offsetX = 0;
        this.offsetY = 0;
        
        // --- Bind des gestionnaires d'événements ---
        // (Tout le reste du constructeur est inchangé)
        this.handlerBtnRetour = this.retourSélection.bind(this);
        this.handlerBtnMélanger = this.mélangerPieces.bind(this);
        this.handlerGererTapMobile = this.gererTapMobile.bind(this);
        this.handlerPlacerPieceMobile = this.placerPieceMobile.bind(this);
        this.handlerCommencerDrag = this.commencerDrag.bind(this);
        this.handlerDéplacer = this.déplacer.bind(this);
        this.handlerFinirDrag = this.finirDrag.bind(this);
        this.handlerTouchStart = this.handleTouchStart.bind(this);
        this.handlerTouchMove = this.handleTouchMove.bind(this);
        this.handlerTouchEnd = this.handleTouchEnd.bind(this);
    }

    /**
     * Méthode utilitaire pour détecter les mobiles.
     */
    estMobile() {
        return window.innerWidth <= 768;
    }

    /**
     * Point d'entrée, appelé au chargement de la page.
     */
    async init() {
        try {
            // On appelle notre script PHP
            const response = await fetch('liste_images.php?v=' + new Date().getTime());
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            const imagesDuServeur = await response.json();
            
            if (imagesDuServeur.length === 0) {
                 this.chargementEl.textContent = 'Aucun puzzle trouvé.';
                 return;
            }
            
            // On stocke la liste [ {thumb, original}, ... ]
            this.catalogueImages = imagesDuServeur;
            
            // On lance le chargement des miniatures (anciennement chargerImages)
            this.chargerMiniatures(imagesDuServeur); 
    
        } catch (error) {
            console.error("Impossible de charger la liste des puzzles:", error);
            this.chargementEl.style.display = 'block';
            this.chargementEl.innerHTML = '<p style="color:red;">Erreur: Impossible de charger les puzzles.<br>Vérifiez le serveur PHP.</p>';
        }
    }
    

    chargerMiniatures(listeImages) {
        let premiereImageChargee = false;
        
        // Fonction pour masquer le loader
        const masquerChargementSiPremiere = () => {
            if (!premiereImageChargee) {
                this.chargementEl.style.display = 'none';
                premiereImageChargee = true;
            }
        };
        
        // On boucle sur la liste TRIÉE
        listeImages.forEach((imageInfo, index) => {
            
            // 1. On crée la balise <img> pour la miniature
            const miniature = document.createElement('img');
            miniature.className = 'miniature';
            miniature.onclick = () => this.démarrerJeu(index); 
            
            // 2. On la rend invisible pour l'instant (pour l'effet de fade-in)
            miniature.style.opacity = 0;
            miniature.style.transition = 'opacity 0.3s ease';
    
            // 3. On attache les écouteurs AVANT de définir la source
            miniature.onload = () => {
                // C'est la première ? On cache le loader.
                masquerChargementSiPremiere();
                // L'image est chargée, on la fait apparaître
                miniature.style.opacity = 1; 
            };
            
            miniature.onerror = () => {
                // On cache aussi le loader si ça plante
                masquerChargementSiPremiere();
                console.error(`Erreur de chargement miniature: ${imageInfo.thumb}`);
                // On peut la marquer comme cassée
                miniature.style.border = '2px solid #8c3b3b';
                miniature.style.opacity = 1; // On la montre quand même (cassée)
            };
    
            // 4. On AJOUTE au DOM (dans le bon ordre, mais encore invisible)
            this.conteneurMiniatures.appendChild(miniature);
            
            // 5. C'est SEULEMENT MAINTENANT qu'on lance le chargement
            miniature.src = imageInfo.thumb; 
        });
    }


    /**
     * Configure et démarre une partie avec l'image sélectionnée.
     */
    démarrerJeu(index) {
    // 1. Récupérer les infos de l'image cliquée depuis notre catalogue
        const imageInfo = this.catalogueImages[index];
        
        if (!imageInfo) {
            alert("Erreur: Impossible de trouver les informations pour ce puzzle.");
            return;
        }
    
        // Affiche l'écran de jeu mais avec un message de chargement
        this.écranSélection.style.display = 'none';
        this.écranJeu.style.display = 'block';
        this.messageVictoire.style.display = 'none';
    
        // Nettoyer les canvas et afficher un message de chargement pour le puzzle
        this.configurerCanvas(); // Configure la taille
        this.ctxResolution.fillStyle = '#fff';
        this.ctxResolution.fillRect(0, 0, this.canvasResolution.width, this.canvasResolution.height);
        this.ctxResolution.fillStyle = '#555';
        this.ctxResolution.font = '16px system-ui';
        this.ctxResolution.textAlign = 'center';
        this.ctxResolution.fillText("Chargement du puzzle...", this.canvasResolution.width / 2, this.canvasResolution.height / 2);
        this.ctxPieces.clearRect(0, 0, this.canvasPieces.width, this.canvasPieces.height);
    
    
        // 2. Créer l'objet Image pour le puzzle et le charger
        this.imageActive = new Image();
        
        // 3. TOUTE la logique d'initialisation doit être dans le "onload"
        this.imageActive.onload = () => {
            // L'image est chargée, on peut enfin initialiser le puzzle
            this.initialiserPuzzle(); // <-- Utilise this.imageActive.width/height
            this.attacherListeners();
            this.dessinerTout(); // <-- Dessine le puzzle final
        };
        
        this.imageActive.onerror = () => {
             alert(`Erreur lors du chargement de l'image originale: ${imageInfo.original}`);
             this.retourSélection();
        };
        
        // 4. On lance le chargement de l'image ORIGINALE
        this.imageActive.src = imageInfo.original;
    }

    /**
     * Configure la taille des canvas en fonction de l'appareil.
     */
    configurerCanvas() {
    // Dans cette version, les canvas sont configurés DANS initialiserPuzzle(),
    // car ils dépendent de la taille réelle de l'image chargée.
    // Cette fonction peut simplement s'assurer que les éléments sont visibles
    // ou nettoyer si nécessaire, avant que l'image ne soit disponible.
    // L'important est de ne pas définir des dimensions ici, car elles seront écrasées.

    // On peut réinitialiser le style de grid pour le moment
    this.zonesJeuEl.innerHTML = '';
    this.zonesJeuEl.style.gridTemplateColumns = 'none';
    this.zonesJeuEl.style.gridTemplateRows = 'none';

    // Rendre les canvas visibles mais avec une taille 0 pour l'instant
    this.canvasPieces.style.display = 'block';
    this.canvasResolution.style.display = 'block';
    this.canvasOverlay.style.display = 'block';
    
    // Centrage (peut être ajusté en CSS)
    this.zonesJeuEl.style.position = 'absolute'; // Assurez-vous que zones-jeu est positionné
    this.zonesJeuEl.style.left = '50%';
    this.zonesJeuEl.style.top = '50%';
    this.zonesJeuEl.style.transform = 'translate(-50%, -50%)';
}
    
    /**
     * Calcule et stocke les positions de départ des pièces (utilisé pour les mélanger et les renvoyer).
     * CORRIGE LE BUG de duplication de code et le bug de snap-back.
     */
    calculerPositionsGrillePieces() {
        this.positionsGrillePieces = [];
        const largeurPiece = 150; // La taille d'affichage de la pièce
        const hauteurPiece = 150;
        const espaceX = this.estMobile() ? (this.canvasPieces.width - largeurPiece * 2) / 3 : 50;
        const espaceY = this.estMobile() ? 30 : 50;
        
        for (let row = 0; row < 3; row++) {
            for (let col = 0; col < 2; col++) {
                this.positionsGrillePieces.push({
                    x: espaceX + col * (largeurPiece + espaceX),
                    y: espaceY + row * (hauteurPiece + espaceY)
                });
            }
        }
    }

    /**
     * Crée le tableau des pièces du puzzle.
     */
    
    
    
    initialiserPuzzle() {
        this.pieces = [];
        this.piècesPlacées = 0;
        this.compteurEl.textContent = `Pièces placées : 0/${this.nombrePiecesTotal}`;
        this.messageVictoire.style.display = 'none';
    
        // *** NOUVEAUTÉ ICI : Calcul des dimensions basé sur l'image active ***
        const imageWidth = this.imageActive.naturalWidth;
        const imageHeight = this.imageActive.naturalHeight;
    
        // Définir le nombre de pièces de manière adaptative
        // Par exemple, on veut 3 rangées par défaut, et on ajuste les colonnes
        // Ou inversement, on veut 3 colonnes, et on ajuste les rangées
        const piecesParRangee = 3; // On commence avec 3 pièces par rangée
        this.pieceWidth = Math.floor(imageWidth / piecesParRangee);
        // On calcule la hauteur de la pièce pour garder le ratio, et on arrondit à un entier
        this.pieceHeight = Math.floor(this.pieceWidth * (imageHeight / imageWidth)); 
        
        // Le nombre de rangées sera déterminé par la hauteur de la pièce
        const nombreRangees = Math.floor(imageHeight / this.pieceHeight);
    
        // On s'assure que les dimensions finales correspondent exactement
        this.pieceWidth = Math.floor(imageWidth / piecesParRangee); // Recalcul au cas où l'arrondi a bougé
        this.pieceHeight = Math.floor(imageHeight / nombreRangees);
    
    
        // Redimensionner les canvas pour correspondre à l'image originale
        this.canvasPieces.width = this.canvasResolution.width = this.canvasOverlay.width = imageWidth;
        this.canvasPieces.height = this.canvasResolution.height = this.canvasOverlay.height = imageHeight;
        
        // Positionner les canvas pour qu'ils soient centrés (ajuste ton CSS si nécessaire)
        this.canvasPieces.style.left = (window.innerWidth - imageWidth) / 2 + 'px';
        this.canvasPieces.style.top = (window.innerHeight - imageHeight) / 2 + 'px'; // À ajuster selon ta structure
        // Fais de même pour canvasResolution et canvasOverlay si tu les positionnes en absolu
    
        // Règle les zones de jeu
        this.zonesJeuEl.innerHTML = ''; // Nettoyer les anciennes zones
        this.zonesJeuEl.style.width = `${imageWidth}px`;
        this.zonesJeuEl.style.height = `${imageHeight}px`;
        this.zonesJeuEl.style.gridTemplateColumns = `repeat(${piecesParRangee}, ${this.pieceWidth}px)`;
        this.zonesJeuEl.style.gridTemplateRows = `repeat(${nombreRangees}, ${this.pieceHeight}px)`;
    
        this.nombrePiecesTotal = piecesParRangee * nombreRangees;
        this.compteurEl.textContent = `Pièces placées : 0/${this.nombrePiecesTotal}`;
    
        // Générer les pièces et les positions
        this.positionsGrillePieces = [];
        for (let r = 0; r < nombreRangees; r++) {
            for (let c = 0; c < piecesParRangee; c++) {
                const x = c * this.pieceWidth;
                const y = r * this.pieceHeight;
                this.positionsGrillePieces.push({ x, y });
    
                // Créer les zones de dépôt
                const zone = document.createElement('div');
                zone.className = 'zone-depot';
                zone.dataset.x = x;
                zone.dataset.y = y;
                zone.style.width = `${this.pieceWidth}px`;
                zone.style.height = `${this.pieceHeight}px`;
                this.zonesJeuEl.appendChild(zone);
    
                // Créer la pièce de puzzle
                const piece = {
                    id: this.pieces.length,
                    originalX: x,
                    originalY: y,
                    currentX: 0, // Sera mélangée plus tard
                    currentY: 0, // Sera mélangée plus tard
                    estPlacee: false,
                    estSelectionnee: false,
                };
                this.pieces.push(piece);
            }
        }
    
        this.mélangerPieces(); // Mélange les pièces après leur création
    }
    
    

    /**
     * Attache tous les écouteurs d'événements nécessaires pour le jeu.
     */
    attacherListeners() {
        this.btnRetour.addEventListener('click', this.handlerBtnRetour);
        this.btnMélanger.addEventListener('click', this.handlerBtnMélanger);

        if (this.estMobile()) {
            // MOBILE: Logique de "Tap-Tap"
            this.canvasPieces.addEventListener('click', this.handlerGererTapMobile);
            this.canvasResolution.addEventListener('click', this.handlerPlacerPieceMobile);
        } else {
            // DESKTOP/TABLET: Logique de "Drag & Drop"
            this.canvasPieces.addEventListener('mousedown', this.handlerCommencerDrag);
            document.addEventListener('mousemove', this.handlerDéplacer);
            document.addEventListener('mouseup', this.handlerFinirDrag);
            
            // Support tactile pour le Drag & Drop
            this.canvasPieces.addEventListener('touchstart', this.handlerTouchStart);
            document.addEventListener('touchmove', this.handlerTouchMove);
            document.addEventListener('touchend', this.handlerTouchEnd);
        }
    }

    /**
     * Retire proprement tous les écouteurs pour éviter les fuites de mémoire.
     */
    detacherListeners() {
        this.btnRetour.removeEventListener('click', this.handlerBtnRetour);
        this.btnMélanger.removeEventListener('click', this.handlerBtnMélanger);

        // Retire tous les listeners, qu'ils soient mobiles ou non (plus simple)
        this.canvasPieces.removeEventListener('click', this.handlerGererTapMobile);
        this.canvasResolution.removeEventListener('click', this.handlerPlacerPieceMobile);
        
        this.canvasPieces.removeEventListener('mousedown', this.handlerCommencerDrag);
        document.removeEventListener('mousemove', this.handlerDéplacer);
        document.removeEventListener('mouseup', this.handlerFinirDrag);
        
        this.canvasPieces.removeEventListener('touchstart', this.handlerTouchStart);
        document.removeEventListener('touchmove', this.handlerTouchMove);
        document.removeEventListener('touchend', this.handlerTouchEnd);
    }

    // --- LOGIQUE DE JEU ---

    /**
     * Mélange les pièces et réinitialise le plateau.
     */
    mélangerPieces() {
        let positionsDisponibles = [0, 1, 2, 3, 4, 5];
        
        // Mélange de Fisher-Yates
        for (let i = positionsDisponibles.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [positionsDisponibles[i], positionsDisponibles[j]] = [positionsDisponibles[j], positionsDisponibles[i]];
        }
        
        this.pieces.forEach((piece, index) => {
            const posIndex = positionsDisponibles[index];
            // Utilise les positions pré-calculées
            piece.x = this.positionsGrillePieces[posIndex].x;
            piece.y = this.positionsGrillePieces[posIndex].y;
            piece.positionInitialeIndex = posIndex;
            piece.placée = false;
        });
        
        this.piècesPlacées = 0;
        this.mettreAJourCompteur();
        this.messageVictoire.style.display = 'none';
        
        this.dessinerTout();
    }
    
    /**
     * Revient à l'écran de sélection.
     */
    retourSélection() {
        this.écranJeu.style.display = 'none';
        this.écranSélection.style.display = 'flex'; // ou 'block' selon ton CSS
        
        this.detacherListeners();
        
        // Réinitialise l'état
        this.pieces = [];
        this.indexPieceSelectionnee = -1;
        this.pieceSelectionneeParTap = null;
        this.imageActive = null;
    }

    /**
     * Met à jour le compteur de pièces.
     */
    mettreAJourCompteur() {
        this.compteurEl.textContent = `${this.piècesPlacées}/6 pièces`;
    }

    /**
     * Vérifie si le jeu est gagné et affiche le message.
     */
    verifierVictoire() {
        if (this.piècesPlacées === 6) {
            this.messageVictoire.style.display = 'block';
        }
    }

    // --- MÉTHODES DE DESSIN ---

    /**
     * Redessine les deux canvas principaux.
     */
    dessinerTout() {
        this.dessinerPieces();
        this.dessinerZoneResolution();
    }

    /**
     * Dessine les pièces dans la zone de départ.
     */
    dessinerPieces() {
        this.ctxPieces.clearRect(0, 0, this.canvasPieces.width, this.canvasPieces.height);
        
        for (let i = 0; i < this.pieces.length; i++) {
            // Ne dessine pas la pièce si elle est en cours de drag ou si elle est placée
            if (i === this.indexPieceSelectionnee || this.pieces[i].placée) continue;
            
            const piece = this.pieces[i];
            
            this.ctxPieces.drawImage(
                this.imageActive,
                piece.sx, piece.sy, piece.sWidth, piece.sHeight,
                piece.x, piece.y, piece.width, piece.height
            );
            
            this.ctxPieces.strokeStyle = '#000';
            this.ctxPieces.lineWidth = 3;
            this.ctxPieces.strokeRect(piece.x, piece.y, piece.width, piece.height);
        }
    }

    /**
     * Dessine les pièces (pour mobile) en mettant la pièce tapée en surbrillance.
     */
    dessinerPiecesSurbrillance() {
        this.ctxPieces.clearRect(0, 0, this.canvasPieces.width, this.canvasPieces.height);
        
        for (let i = 0; i < this.pieces.length; i++) {
            if (this.pieces[i].placée) continue;
            
            const piece = this.pieces[i];
            const estSelectionnee = (i === this.pieceSelectionneeParTap);
            
            if (estSelectionnee) {
                this.ctxPieces.save();
                this.ctxPieces.shadowColor = 'rgba(255, 140, 148, 0.8)';
                this.ctxPieces.shadowBlur = 20;
            }
            
            this.ctxPieces.drawImage(
                this.imageActive,
                piece.sx, piece.sy, piece.sWidth, piece.sHeight,
                piece.x, piece.y, piece.width, piece.height
            );
            
            this.ctxPieces.strokeStyle = estSelectionnee ? '#FF0000' : '#000';
            this.ctxPieces.lineWidth = estSelectionnee ? 6 : 3;
            this.ctxPieces.strokeRect(piece.x, piece.y, piece.width, piece.height);
            
            if (estSelectionnee) {
                this.ctxPieces.restore();
            }
        }
    }

    /**
     * Dessine la grille de résolution et les pièces déjà placées.
     */
    dessinerZoneResolution() {
        this.ctxResolution.clearRect(0, 0, this.canvasResolution.width, this.canvasResolution.height);
        
        this.ctxResolution.fillStyle = '#fff';
        this.ctxResolution.fillRect(0, 0, this.canvasResolution.width, this.canvasResolution.height);
        
        this.ctxResolution.strokeStyle = '#999';
        this.ctxResolution.lineWidth = 2;
        
        for (let row = 0; row < 3; row++) {
            for (let col = 0; col < 2; col++) {
                const x = col * this.pieceWidth;
                const y = row * this.pieceHeight;
                this.ctxResolution.strokeRect(x, y, this.pieceWidth, this.pieceHeight);
            }
        }
        
        this.pieces.forEach(piece => {
            if (piece.placée) {
                this.ctxResolution.drawImage(
                    this.imageActive,
                    piece.sx, piece.sy, piece.sWidth, piece.sHeight,
                    piece.positionFinaleX, piece.positionFinaleY, this.pieceWidth, this.pieceHeight
                );
            }
        });
    }
    
    /**
     * Dessine la pièce en cours de drag sur le canvas d'overlay.
     */
    dessinerPieceOverlay(piece, x, y) {
        this.ctxOverlay.clearRect(0, 0, this.canvasOverlay.width, this.canvasOverlay.height);
        
        this.ctxOverlay.save();
        this.ctxOverlay.shadowColor = 'rgba(0, 0, 0, 0.6)';
        this.ctxOverlay.shadowBlur = 20;
        this.ctxOverlay.shadowOffsetX = 8;
        this.ctxOverlay.shadowOffsetY = 8;
        
        this.ctxOverlay.drawImage(
            this.imageActive,
            piece.sx, piece.sy, piece.sWidth, piece.sHeight,
            x, y, piece.width, piece.height
        );
        
        this.ctxOverlay.shadowColor = 'transparent';
        this.ctxOverlay.strokeStyle = '#FF0000';
        this.ctxOverlay.lineWidth = 6;
        this.ctxOverlay.strokeRect(x, y, piece.width, piece.height);
        
        this.ctxOverlay.restore();
    }
    
    // --- GESTIONNAIRES D'ÉVÉNEMENTS ---

    /**
     * Méthode utilitaire pour obtenir les coordonnées d'un événement (souris ou tactile).
     */
    getCoords(e) {
        let clientX, clientY;
        if (e.clientX) {
            clientX = e.clientX;
            clientY = e.clientY;
        } else if (e.touches && e.touches[0]) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else if (e.changedTouches && e.changedTouches[0]) {
            // Pour touchend
            clientX = e.changedTouches[0].clientX;
            clientY = e.changedTouches[0].clientY;
        }
        return { clientX, clientY };
    }

    // --- Handlers pour Mobile (Tap-Tap) ---

    gererTapMobile(e) {
        const { clientX, clientY } = this.getCoords(e);
        const rect = this.canvasPieces.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        
        for (let i = this.pieces.length - 1; i >= 0; i--) {
            const p = this.pieces[i];
            if (!p.placée && x >= p.x && x <= p.x + p.width && y >= p.y && y <= p.y + p.height) {
                this.pieceSelectionneeParTap = i;
                this.dessinerPiecesSurbrillance();
                return;
            }
        }
    }

    placerPieceMobile(e) {
        if (this.pieceSelectionneeParTap === null) return;
        
        const { clientX, clientY } = this.getCoords(e);
        const rect = this.canvasResolution.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        
        if (x >= 0 && x <= this.canvasResolution.width && y >= 0 && y <= this.canvasResolution.height) {
            const col = Math.floor(x / this.pieceWidth);
            const row = Math.floor(y / this.pieceHeight);
            
            const piece = this.pieces[this.pieceSelectionneeParTap];
            
            if (col === piece.col && row === piece.row) {
                piece.placée = true;
                this.piècesPlacées++;
                this.mettreAJourCompteur();
                this.dessinerZoneResolution();
                this.verifierVictoire();
            }
        }
        
        this.pieceSelectionneeParTap = null;
        this.dessinerPieces(); // Redessine normal
    }
    
    // --- Handlers pour Desktop/Tablet (Drag & Drop) ---

    commencerDrag(e) {
        const { clientX, clientY } = this.getCoords(e);
        if (clientX === undefined) return;
        
        const rect = this.canvasPieces.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        
        this.indexPieceSelectionnee = -1;
        
        for (let i = this.pieces.length - 1; i >= 0; i--) {
            const p = this.pieces[i];
            if (!p.placée && x >= p.x && x <= p.x + p.width && y >= p.y && y <= p.y + p.height) {
                this.indexPieceSelectionnee = i;
                this.offsetX = x - p.x;
                this.offsetY = y - p.y;
                this.dessinerPieces(); // Redessine la grille sans la pièce
                break;
            }
        }
    }

    déplacer(e) {
        if (this.indexPieceSelectionnee < 0) return;
        
        const { clientX, clientY } = this.getCoords(e);
        if (clientX === undefined) return;

        const piece = this.pieces[this.indexPieceSelectionnee];
        const rectZones = this.zonesJeuEl.getBoundingClientRect();
        
        // Calcule la position relative au conteneur global pour l'overlay
        const overlayX = clientX - rectZones.left - this.offsetX;
        const overlayY = clientY - rectZones.top - this.offsetY;
        
        this.dessinerPieceOverlay(piece, overlayX, overlayY);
    }

    finirDrag(e) {
        if (this.indexPieceSelectionnee < 0) return;
        
        this.ctxOverlay.clearRect(0, 0, this.canvasOverlay.width, this.canvasOverlay.height);
        
        const { clientX, clientY } = this.getCoords(e);
        if (clientX === undefined) return;
        
        const rect = this.canvasResolution.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        
        const piece = this.pieces[this.indexPieceSelectionnee];
        
        let dropValide = false;
        
        // Vérifie si le lâcher est dans la zone de résolution
        if (x >= 0 && x <= this.canvasResolution.width && y >= 0 && y <= this.canvasResolution.height) {
            const col = Math.floor(x / this.pieceWidth);
            const row = Math.floor(y / this.pieceHeight);
            
            // Vérifie si c'est le bon emplacement
            if (col === piece.col && row === piece.row) {
                piece.placée = true;
                this.piècesPlacées++;
                this.mettreAJourCompteur();
                this.dessinerZoneResolution();
                this.verifierVictoire();
                dropValide = true;
            }
        }
        
        // Si le drop n'est pas valide, renvoie la pièce à sa position de départ
        if (!dropValide) {
            // BUG CORRIGÉ : utilise les positions calculées (this.positionsGrillePieces)
            // au lieu d'un tableau codé en dur (positionsGrille)
            piece.x = this.positionsGrillePieces[piece.positionInitialeIndex].x;
            piece.y = this.positionsGrillePieces[piece.positionInitialeIndex].y;
        }
        
        this.indexPieceSelectionnee = -1;
        this.dessinerPieces(); // Redessine la grille de départ
    }

    // --- Wrappers pour les événements tactiles (Drag & Drop) ---
    
    handleTouchStart(e) {
        e.preventDefault();
        this.commencerDrag(e);
    }

    handleTouchMove(e) {
        e.preventDefault();
        this.déplacer(e);
    }

    handleTouchEnd(e) {
        e.preventDefault();
        this.finirDrag(e);
    }
}

// --- Point d'entrée de l'application ---
// On attend que la page soit chargée pour créer et initialiser le jeu.
window.addEventListener('load', () => {
    const monJeuDePuzzle = new PuzzleGame();
    monJeuDePuzzle.init();
});