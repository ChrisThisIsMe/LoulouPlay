<?php
/**
 * Page d'accueil de Loulou Play
 * Affiche les jeux disponibles sous forme de tuiles
 */

// Chargement de la configuration
require_once 'config.php';

// Titre de la page
$page_title = 'Accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_TAGLINE; ?> - Jeux Educatifs pour apprendre en s'amusant">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Loulou Play - Site éducatif pour enfants">
    <meta property="og:description" content="Jeux éducatifs gratuits et sans publicité pour aider votre enfant à développer son langage, compter et mémoriser.  Créé par un papa pour sa fille !">
    <meta property="og:image" content="https://loulouplay.com/img/mascotte-hibou-reseau-sociaux.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="https://loulouplay.com/">
    <meta property="og:site_name" content="Loulou Play - Site éducatif pour enfants">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Loulou Play - Site éducatif pour enfants">
    <meta property="og:description" content="Jeux éducatifs gratuits et sans publicité pour aider votre enfant à développer son langage, compter et mémoriser. Créé par un papa pour sa fille !">
    <meta name="twitter:image" content="https://loulouplay.com/img/mascotte-hibou.png">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo PATH_IMG; ?>mascotte-hibou.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Fredoka', Arial, sans-serif; 
            background: linear-gradient(180deg, #FFF9C4 0%, #FFEB3B 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* === ELEMENTS DECORATIFS SVG === */
        .decoration-svg {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }
        
        /* Nuage bleu turquoise haut gauche */
        .nuage-1 {
            top: 5%;
            left: 3%;
            width: 180px;
            animation: flotte-lent 8s ease-in-out infinite;
        }
        
        /* Fleur orange haut gauche */
        .fleur-1 {
            top: 12%;
            left: 8%;
            width: 80px;
            animation: rotation-lente 20s linear infinite;
        }
        
        /* Feuille verte haut gauche */
        .feuille-1 {
            top: 28%;
            left: 4%;
            width: 70px;
            animation: balance 5s ease-in-out infinite;
        }
        
        /* Fleur rose bas gauche */
        .fleur-2 {
            bottom: 15%;
            left: 6%;
            width: 65px;
            animation: rotation-lente 15s linear infinite reverse;
        }
        
        /* Feuille verte bas gauche */
        .feuille-2 {
            bottom: 25%;
            left: 10%;
            width: 90px;
            animation: balance 6s ease-in-out infinite 1s;
        }
        
        /* Papillon orange bas gauche */
        .papillon-1 {
            bottom: 35%;
            left: 12%;
            width: 70px;
            animation: vole 10s ease-in-out infinite;
        }
        
        /* Feuille bleue haut droite */
        .feuille-3 {
            top: 15%;
            right: 8%;
            width: 80px;
            animation: balance 7s ease-in-out infinite 2s;
        }
        
        /* Papillon orange droite */
        .papillon-2 {
            top: 25%;
            right: 15%;
            width: 90px;
            animation: vole 12s ease-in-out infinite 3s;
        }
        
        /* Fleur rose droite */
        .fleur-3 {
            top: 45%;
            right: 10%;
            width: 85px;
            animation: rotation-lente 18s linear infinite;
        }
        
        /* Feuille verte droite */
        .feuille-4 {
            top: 60%;
            right: 5%;
            width: 75px;
            animation: balance 5.5s ease-in-out infinite 1.5s;
        }
        
        /* Tournesol bas droite */
        .tournesol {
            bottom: 8%;
            right: 7%;
            width: 70px;
            animation: rotation-lente 25s linear infinite;
        }
        
        /* Nuage blanc haut droite */
        .nuage-2 {
            top: 8%;
            right: 25%;
            width: 100px;
            animation: flotte-lent 9s ease-in-out infinite 2s;
        }
        
        /* Points décoratifs */
        .point-deco {
            position: fixed;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            opacity: 0.6;
            z-index: 0;
        }
        
        .point-1 { top: 20%; left: 18%; background: #FF9800; }
        .point-2 { top: 35%; left: 20%; background: #FFB74D; }
        .point-3 { top: 50%; right: 22%; background: #FF9800; }
        .point-4 { top: 65%; right: 18%; background: #FFD54F; }
        .point-5 { bottom: 20%; left: 25%; background: #FF9800; }
        .point-6 { top: 10%; right: 35%; background: #FFB74D; }
        
        /* Animations */
        @keyframes flotte-lent {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
        }
        
        @keyframes rotation-lente {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes balance {
            0%, 100% { transform: rotate(-8deg); }
            50% { transform: rotate(8deg); }
        }
        
        @keyframes vole {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(-20px, -30px) rotate(-10deg); }
            50% { transform: translate(0, -50px) rotate(5deg); }
            75% { transform: translate(20px, -30px) rotate(-5deg); }
        }
        
        /* === MASCOTTE AVEC LIGNE === */
        .mascotte-coin {
            position: absolute;
            top: 50%;
            right: 5%;
            transform: translateY(-50%);
            width: 180px;
            height: auto;
            z-index: 1001;
            animation: flotte-mascotte 4s ease-in-out infinite;
            filter: drop-shadow(0 6px 15px rgba(0,0,0,0.3));
        }

        
        @keyframes flotte-mascotte {
            0%, 100% { transform: translateY(-50%) rotate(-8deg); }
            50% { transform: translateY(-58%) rotate(8deg); }
        }
        
        .break-mobile {
            display: inline;
        }

        /* === HEADER === */
        .site-header {
            text-align: center;
            padding: 0 20px 15px;
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }

        
        .bienvenue {
            font-size: 2.8em;
            font-weight: 700;
            color: #FFFFFF;
            background: linear-gradient(135deg, #7C4DFF 0%, #5E35B1 100%);
            text-transform: uppercase;
            letter-spacing: 8px;
            margin-bottom: 15px;
            display: inline-block;
            padding: 12px 35px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(94, 53, 177, 0.5);
            transform: rotate(-2deg);
            animation: rebond 3s ease-in-out infinite;
        }
        
        @keyframes rebond {
            0%, 100% { transform: rotate(-2deg) translateY(0); }
            50% { transform: rotate(2deg) translateY(-5px); }
        }
        
        .site-header h1 {
            font-size: 7.5em;
            font-weight: 700;
            color: #FF6B35;
            margin-bottom: 18px;
            line-height: 1;
            letter-spacing: 3px;
            text-shadow: 
                4px 4px 0px rgba(255, 255, 255, 0.9),
                -2px -2px 0px rgba(255, 255, 255, 0.5),
                0 8px 25px rgba(255, 107, 53, 0.4);
            position: relative;
        }
        
        
        .site-header .tagline {
            font-size: 2.8em;
            color: #FFFFFF;
            background: linear-gradient(135deg, #5E35B1 0%, #7C4DFF 100%);
            font-weight: 700;
            letter-spacing: 2px;
            display: inline-block;
            padding: 10px 30px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(94, 53, 177, 0.5);
        }

        
        /* === CONTENU PRINCIPAL === */
        .conteneur-principal {
            max-width: 1200px;
            margin: 50px auto 120px;
            position: relative;
            z-index: 1;
        }
        
        .grille-jeux {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
                /* === CARTES DE JEUX === */
        .carte-jeu {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 35px;
            padding: 50px 35px;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 5px solid transparent;
        }
        
        .carte-jeu.actif {
            cursor: pointer;
            border-color: #667eea;
            background: rgba(255, 255, 255, 1);
        }
        
        .carte-jeu.actif:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
        }
        
        .carte-jeu.desactive {
            opacity: 0.45;
            cursor: not-allowed;
            filter: grayscale(70%);
            background: rgba(255, 253, 231, 0.8);
        }
        
        .carte-jeu .icone {
            font-size: 6.5em;
            margin-bottom: 25px;
            display: block;
        }
        
        .carte-jeu.actif .icone {
            animation: pulse-icone 2.5s ease-in-out infinite;
        }
        
        @keyframes pulse-icone {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        
        .carte-jeu h2 {
            font-size: 2.8em;
            color: #2C3E50;
            margin-bottom: 15px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .carte-jeu p {
            font-size: 1.35em;
            color: #7f8c8d;
            margin-bottom: 32px;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .carte-jeu .bouton {
            display: inline-block;
            padding: 20px 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            font-size: 1.5em;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.45);
            border: none;
            letter-spacing: 1px;
        }
        
        .carte-jeu.actif .bouton:hover {
            transform: scale(1.12);
            box-shadow: 0 10px 35px rgba(102, 126, 234, 0.65);
        }
        
        .badge-bientot {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #BDBDBD 0%, #9E9E9E 100%);
            color: white;
            font-weight: 700;
            border-radius: 50px;
            font-size: 1.25em;
            box-shadow: 0 6px 15px rgba(0,0,0,0.25);
            letter-spacing: 1px;
        }
        
        
        /* Décorations supplémentaires bas de page */
        .fleur-bas-1 {
            bottom: 10%;
            left: 30%;
            width: 70px;
            animation: rotation-lente 22s linear infinite;
        }
        
        .fleur-bas-2 {
            bottom: 12%;
            right: 28%;
            width: 65px;
            animation: rotation-lente 19s linear infinite reverse;
        }
        
        .feuille-bas-1 {
            bottom: 18%;
            left: 38%;
            width: 60px;
            animation: balance 6s ease-in-out infinite 2s;
        }
        
        .point-7 { bottom: 15%; left: 42%; background: #FFB74D; }
        .point-8 { bottom: 20%; right: 35%; background: #FF9800; }
        .point-9 { bottom: 10%; left: 50%; background: #FFD54F; }

        /* === RESPONSIVE === */
    @media (max-width: 1024px) {
        .grille-jeux {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Forcer le contenu à passer derrière le menu mobile */
    @media (max-width: 768px) {
        .conteneur-principal {
            z-index: 0;
        }
        
        .carte-jeu {
            z-index: 0;
        }
    }
    
    @media (max-width: 750px) {
        /* Titres header */
        .bienvenue {
            font-size: 1.6em;
            letter-spacing: 4px;
            padding: 8px 20px;
        }
        
        .break-mobile {
            display: block;
        }
                
       .site-header h1 {
            font-size: 3.5em;
            text-shadow: 
                3px 3px 0px rgba(255, 255, 255, 0.9),
                -1px -1px 0px rgba(255, 255, 255, 0.5),
                0 5px 15px rgba(255, 107, 53, 0.4);
            text-align: center;
            transform: translateX(-50px);
        }
                
        .site-header .tagline {
            font-size: 1.5em;
            letter-spacing: 1px;
            padding: 8px 20px;
        }
        
        /* Grille de jeux */
        .grille-jeux {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        
        .carte-jeu {
            padding: 35px 25px;
        }
        
        .carte-jeu .icone {
            font-size: 4em;
        }
        
        .carte-jeu h2 {
            font-size: 1.8em;
        }
        
        /* Mascotte */
        .mascotte-coin {
            width: 100px;
            height: auto;
        }
        
        /* Masquer décorations qui gênent sur mobile */
        .fleur-1, .feuille-1, .fleur-3, .feuille-4, .papillon-2, .tournesol, .nuage-2 {
            display: none;
        }
        
        .point-deco {
            width: 10px;
            height: 10px;
        }
    }

    
    </style>
</head>
<body>
    
    <!-- === DECORATIONS SVG === -->
    
    <!-- Nuage bleu turquoise -->
    <svg class="decoration-svg nuage-1" viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="50" cy="60" rx="40" ry="30" fill="#4DD0E1"/>
        <ellipse cx="100" cy="50" rx="50" ry="35" fill="#26C6DA"/>
        <ellipse cx="150" cy="60" rx="40" ry="30" fill="#4DD0E1"/>
    </svg>
    
    <!-- Fleur orange -->
    <svg class="decoration-svg fleur-1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="30" r="18" fill="#FF7043"/>
        <circle cx="70" cy="50" r="18" fill="#FF7043"/>
        <circle cx="50" cy="70" r="18" fill="#FF7043"/>
        <circle cx="30" cy="50" r="18" fill="#FF7043"/>
        <circle cx="50" cy="50" r="15" fill="#FFD54F"/>
    </svg>
    
    <!-- Feuille verte -->
    <svg class="decoration-svg feuille-1" viewBox="0 0 60 100" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="30" cy="40" rx="25" ry="35" fill="#66BB6A" transform="rotate(-20 30 40)"/>
        <ellipse cx="30" cy="65" rx="20" ry="30" fill="#81C784" transform="rotate(15 30 65)"/>
    </svg>
    
    <!-- Fleur rose -->
    <svg class="decoration-svg fleur-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="30" r="16" fill="#F06292"/>
        <circle cx="68" cy="50" r="16" fill="#F06292"/>
        <circle cx="50" cy="68" r="16" fill="#F06292"/>
        <circle cx="32" cy="50" r="16" fill="#F06292"/>
        <circle cx="50" cy="50" r="12" fill="#FFE082"/>
    </svg>
    
    <!-- Feuille verte 2 -->
    <svg class="decoration-svg feuille-2" viewBox="0 0 80 120" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="40" cy="50" rx="30" ry="45" fill="#66BB6A" transform="rotate(-15 40 50)"/>
        <ellipse cx="40" cy="80" rx="25" ry="35" fill="#81C784" transform="rotate(10 40 80)"/>
    </svg>
    
    <!-- Papillon orange -->
    <svg class="decoration-svg papillon-1" viewBox="0 0 100 80" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="30" cy="35" rx="25" ry="30" fill="#FF7043"/>
        <ellipse cx="70" cy="35" rx="25" ry="30" fill="#FF7043"/>
        <ellipse cx="30" cy="55" rx="20" ry="22" fill="#FFB74D"/>
        <ellipse cx="70" cy="55" rx="20" ry="22" fill="#FFB74D"/>
        <rect x="48" y="20" width="4" height="50" rx="2" fill="#5D4037"/>
    </svg>
    
    <!-- Feuille bleue -->
    <svg class="decoration-svg feuille-3" viewBox="0 0 70 110" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="35" cy="45" rx="28" ry="40" fill="#42A5F5" transform="rotate(-12 35 45)"/>
        <ellipse cx="35" cy="75" rx="22" ry="32" fill="#64B5F6" transform="rotate(18 35 75)"/>
    </svg>
    
    <!-- Papillon orange 2 -->
    <svg class="decoration-svg papillon-2" viewBox="0 0 110 90" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="32" cy="38" rx="28" ry="33" fill="#FF7043"/>
        <ellipse cx="78" cy="38" rx="28" ry="33" fill="#FF7043"/>
        <ellipse cx="32" cy="62" rx="22" ry="25" fill="#FFB74D"/>
        <ellipse cx="78" cy="62" rx="22" ry="25" fill="#FFB74D"/>
        <rect x="53" y="22" width="4" height="55" rx="2" fill="#5D4037"/>
    </svg>
    
    <!-- Fleur rose 3 -->
    <svg class="decoration-svg fleur-3" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="28" r="18" fill="#EC407A"/>
        <circle cx="72" cy="50" r="18" fill="#EC407A"/>
        <circle cx="50" cy="72" r="18" fill="#EC407A"/>
        <circle cx="28" cy="50" r="18" fill="#EC407A"/>
        <circle cx="50" cy="50" r="14" fill="#FFF59D"/>
    </svg>
    
    <!-- Feuille verte 4 -->
    <svg class="decoration-svg feuille-4" viewBox="0 0 65 105" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="32" cy="42" rx="26" ry="38" fill="#66BB6A" transform="rotate(-18 32 42)"/>
        <ellipse cx="32" cy="70" rx="21" ry="30" fill="#81C784" transform="rotate(12 32 70)"/>
    </svg>
    
    <!-- Tournesol -->
    <svg class="decoration-svg tournesol" viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg">
        <circle cx="45" cy="25" r="15" fill="#FDD835"/>
        <circle cx="65" cy="45" r="15" fill="#FDD835"/>
        <circle cx="45" cy="65" r="15" fill="#FDD835"/>
        <circle cx="25" cy="45" r="15" fill="#FDD835"/>
        <circle cx="45" cy="45" r="18" fill="#FF6F00"/>
    </svg>
    
    <!-- Nuage blanc -->
    <svg class="decoration-svg nuage-2" viewBox="0 0 150 80" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="35" cy="50" rx="30" ry="22" fill="#FFFFFF" opacity="0.9"/>
        <ellipse cx="75" cy="42" rx="38" ry="28" fill="#FFFFFF" opacity="0.9"/>
        <ellipse cx="110" cy="50" rx="30" ry="22" fill="#FFFFFF" opacity="0.9"/>
    </svg>
    
    <!-- Fleurs supplémentaires bas -->
    <svg class="decoration-svg fleur-bas-1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="28" r="17" fill="#FF7043"/>
        <circle cx="72" cy="50" r="17" fill="#FF7043"/>
        <circle cx="50" cy="72" r="17" fill="#FF7043"/>
        <circle cx="28" cy="50" r="17" fill="#FF7043"/>
        <circle cx="50" cy="50" r="13" fill="#FFE082"/>
    </svg>
    
    <svg class="decoration-svg fleur-bas-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="30" r="16" fill="#F06292"/>
        <circle cx="70" cy="50" r="16" fill="#F06292"/>
        <circle cx="50" cy="70" r="16" fill="#F06292"/>
        <circle cx="30" cy="50" r="16" fill="#F06292"/>
        <circle cx="50" cy="50" r="12" fill="#FFF59D"/>
    </svg>
    
    <svg class="decoration-svg feuille-bas-1" viewBox="0 0 60 100" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="30" cy="40" rx="24" ry="34" fill="#66BB6A" transform="rotate(-15 30 40)"/>
        <ellipse cx="30" cy="65" rx="19" ry="28" fill="#81C784" transform="rotate(12 30 65)"/>
    </svg>
    
    <!-- Points supplémentaires -->
    <div class="point-deco point-7"></div>
    <div class="point-deco point-8"></div>
    <div class="point-deco point-9"></div>

    
    <!-- Points décoratifs -->
    <div class="point-deco point-1"></div>
    <div class="point-deco point-2"></div>
    <div class="point-deco point-3"></div>
    <div class="point-deco point-4"></div>
    <div class="point-deco point-5"></div>
    <div class="point-deco point-6"></div>
    
    
    <!-- === HEADER === -->
    <header class="site-header">
        <!-- Navigation principale -->
        <?php include 'includes/nav.php'; ?>
        
        <!-- Mascotte hibou -->
        <img src="<?php echo PATH_IMG; ?>mascotte-hibou.png" alt="Hibou Loulou" class="mascotte-coin">
        
        <div class="bienvenue">Bienvenue sur</div>
        <h1>loulou <span class="break-mobile">play</span></h1>
        <p class="tagline"><?php echo SITE_TAGLINE; ?></p>
        
    </header>
    
    
    <!-- === CONTENU PRINCIPAL === -->
    <main class="conteneur-principal">
        <div class="grille-jeux">
            <?php foreach ($jeux_disponibles as $cle => $jeu): ?>
                <div class="carte-jeu <?php echo $jeu['actif'] ? 'actif' : 'desactive'; ?>"
                     <?php if ($jeu['actif']): ?>
                         onclick="window.location.href='<?php echo $jeu['url']; ?>'"
                     <?php endif; ?>>
                    
                    <span class="icone"><?php echo $jeu['icone']; ?></span>
                    <h2><?php echo htmlspecialchars($jeu['nom']); ?></h2>
                    <p><?php echo htmlspecialchars($jeu['description']); ?></p>
                    
                    <?php if ($jeu['actif']): ?>
                        <a href="<?php echo $jeu['url']; ?>" class="bouton">
                            ⭐­ Jouer !
                        </a>
                    <?php else: ?>
                        <span class="badge-bientot">🔒 Bientôt disponible</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
</body>
</html>