<?php
/**
 * Configuration globale de Loulou Play
 * Site éducatif pour enfants
 */

// === INFORMATIONS GÉNÉRALES ===
define('SITE_NAME', 'Loulou Play');
define('SITE_TAGLINE', 'Site éducatif pour enfants');
define('SITE_URL', 'https://loulouplay.com'); // Adapter selon ton domaine
define('SITE_YEAR', date('Y'));

// === CHEMINS ===
define('PATH_ROOT', '/'); // Chemin racine du site (si le site est dans un sous-dossier, adapter ici)
define('PATH_IMG', 'img/');
define('PATH_INCLUDES', 'includes/');

// === ACTIVATION DES JEUX ===
// Pour activer un jeu : mettre 'actif' => true
// Pour désactiver un jeu : mettre 'actif' => false

$jeux_disponibles = [
    'denombrer' => [
        'actif'       => false,
        'nom'         => 'Les Chiffres',
        'description' => 'Apprends à compter',
        'icone'       => '🔢',
        'gradient'    => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        'url'         => 'denombrer/jeu.php'
    ],
    'lesmots' => [
        'actif'       => true,
        'nom'         => 'Les Mots',
        'description' => 'Ecoutes et apprends à prononcer',
        'icone'       => '📖',
        'gradient'    => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'url'         => 'lesmots/index.php'
    ],
    'puzzles' => [
        'actif'       => false,
        'nom'         => 'Les Puzzles',
        'description' => 'Reconstitue les images',
        'icone'       => '🧩',
        'gradient'    => 'linear-gradient(135deg, #4fracfe 0%, #00f2fe 100%)',
        'url'         => 'puzzles/jeu.php'
    ]
];

// === FONCTION UTILITAIRE ===
// Retourne uniquement les jeux actifs
function getJeuxActifs() {
    global $jeux_disponibles;
    return array_filter($jeux_disponibles, function($jeu) {
        return $jeu['actif'] === true;
    });
}

// === MODE DEBUG (optionnel) ===
// Pour afficher tous les jeux même désactivés en dev
define('DEBUG_MODE', false); // Mettre à true pour tout afficher
?>