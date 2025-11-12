<?php
/**
 * Navigation principale de Loulou Play
 * Responsive avec menu hamburger sur mobile
 */

// Récupération de PATH_ROOT (défini dans config.php)
$path_root = defined('PATH_ROOT') ? PATH_ROOT : '/';
?>

<!-- Overlay mobile (fond semi-transparent) -->
<div class="nav-overlay" id="navOverlay"></div>

<!-- Navigation -->
<nav class="nav-principale" id="navPrincipale">
    <!-- Logo / Titre (optionnel, tu peux le décommenter si besoin) -->
    <!-- <div class="nav-logo">🦉 Loulou Play</div> -->
    
    <!-- Menu desktop -->
    <ul class="nav-menu" id="navMenu">
        <li>
            <a href="<?php echo $path_root; ?>index.php">
                🏠 Accueil
            </a>
        </li>
        <li>
            <a href="<?php echo $path_root; ?>qui-sommes-nous.php">
                ℹ️ Qui sommes-nous
            </a>
        </li>
        <li>
            <a href="<?php echo $path_root; ?>contact.php">
                📧 Contact
            </a>
        </li>
    </ul>
    
    <!-- Bouton hamburger (visible uniquement sur mobile) -->
    <button class="nav-hamburger" id="navHamburger" aria-label="Menu de navigation" aria-expanded="false">
        <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <span class="hamburger-text">MENU</span>
    </button>
</nav>

<style>
    /* === NAVIGATION PRINCIPALE === */
    .nav-principale {
        background: white;
        padding: 15px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 25px;
        margin: 20px 0;
        font-family: 'Fredoka', Arial, sans-serif;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    /* === MENU DESKTOP === */
    .nav-menu {
        list-style: none;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
        margin: 0;
        padding: 0;
    }
    
    .nav-menu li a {
        text-decoration: none;
        color: #6c5ce7;
        font-size: 1.2em;
        font-weight: bold;
        transition: all 0.3s ease;
        padding: 8px 15px;
        border-radius: 15px;
        display: inline-block;
    }
    
    .nav-menu li a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        transform: scale(1.05);
        filter: brightness(1.1);
    }
    
    /* === BOUTON HAMBURGER (caché sur desktop) === */
    .nav-hamburger {
        display: none;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 8px 12px;
        z-index: 100000;
        position: absolute;
        right: 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .hamburger-icon {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 28px;
        height: 24px;
    }
    
    .hamburger-icon span {
        width: 100%;
        height: 3px;
        background: #6c5ce7;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .hamburger-text {
        font-size: 1em;
        font-weight: bold;
        color: #6c5ce7;
        transition: all 0.3s ease;
    }
    
    /* Animation du hamburger quand le menu est ouvert */
    .nav-hamburger.active .hamburger-icon span:nth-child(1) {
        transform: rotate(45deg) translate(8px, 8px);
        background: white;
    }
    
    .nav-hamburger.active .hamburger-icon span:nth-child(2) {
        opacity: 0;
    }
    
    .nav-hamburger.active .hamburger-icon span:nth-child(3) {
        transform: rotate(-45deg) translate(8px, -8px);
        background: white;
    }
    
    .nav-hamburger.active .hamburger-text {
        color: white;
    }
    
    /* === OVERLAY MOBILE (caché par défaut) === */
    .nav-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 99998;
        opacity: 0;
        transition: opacity 0.3s ease;
        isolation: isolate;
        will-change: opacity;
    }
    
    .nav-overlay.active {
        display: block;
        opacity: 1;
    }
    
    /* === RESPONSIVE : MOBILE & TABLETTE === */
    @media (max-width: 768px) {
        /* Rendre le fond de la navigation transparent sur mobile */
        .nav-principale {
            background: transparent;
            box-shadow: none;
            padding: 15px 20px;
        }
        
        /* Afficher le bouton hamburger */
        .nav-hamburger {
            display: flex;
        }
        
        /* Masquer le menu par défaut sur mobile */
        .nav-menu {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            flex-direction: column;
            gap: 30px;
            background: #FFFFFF;
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 99999;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            transition: visibility 0s 0.3s, opacity 0.3s ease;
            isolation: isolate;
            will-change: opacity, visibility;
        }
        
        /* Menu ouvert */
        .nav-menu.active {
            visibility: visible;
            opacity: 1;
            pointer-events: all;
            transition: visibility 0s 0s, opacity 0.3s ease;
        }
        
        /* Liens plus grands sur mobile */
        .nav-menu li a {
            font-size: 1.5em;
            padding: 12px 25px;
            display: block;
            text-align: center;
            min-width: 200px;
        }
        
        /* Ajustement du conteneur de navigation */
        .nav-principale {
            justify-content: center;
        }
    }
    
    /* === PETITS ÉCRANS === */
    @media (max-width: 480px) {
        .nav-menu {
            padding: 40px 30px;
        }
        
        .nav-menu li a {
            font-size: 1.3em;
            min-width: 180px;
        }
        
        .hamburger-icon {
            width: 25px;
            height: 20px;
        }
        
        .hamburger-icon span {
            height: 3px;
        }
        
        .hamburger-text {
            font-size: 0.9em;
        }
    }
</style>

<script>
    // Script pour gérer l'ouverture/fermeture du menu mobile
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('navHamburger');
        const menu = document.getElementById('navMenu');
        const overlay = document.getElementById('navOverlay');
        const menuLinks = menu.querySelectorAll('a');
        
        // Fonction pour ouvrir le menu
        function openMenu() {
            hamburger.classList.add('active');
            menu.classList.add('active');
            overlay.classList.add('active');
            hamburger.setAttribute('aria-expanded', 'true');
            // Empêcher le scroll du body
            document.body.style.overflow = 'hidden';
        }
        
        // Fonction pour fermer le menu
        function closeMenu() {
            hamburger.classList.remove('active');
            menu.classList.remove('active');
            overlay.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
            // Réactiver le scroll du body
            document.body.style.overflow = '';
        }
        
        // Toggle menu au clic sur hamburger
        hamburger.addEventListener('click', function() {
            if (menu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        // Fermer le menu au clic sur l'overlay
        overlay.addEventListener('click', closeMenu);
        
        // Fermer le menu au clic sur un lien
        menuLinks.forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
        
        // Fermer le menu avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menu.classList.contains('active')) {
                closeMenu();
            }
        });
    });
</script>