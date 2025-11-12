<?php
// --- CONFIGURATION ---
// Chemin vers les images originales
$dossierOriginaux = 'images/'; 
// Chemin où les miniatures seront créées
$dossierMiniatures = 'images/thumbs/';
// Largeur maximale des miniatures
$largeurMiniature = 150; 
// ---------------------

// --- VÉRIFICATION ROBUSTE DU DOSSIER MINIATURES ---

// On s'assure que le dossier des miniatures existe
if (!file_exists($dossierMiniatures)) {
    // On tente de le crée avec les permissions 775 (écriture pour le propriétaire ET le groupe)
    // Le "true" est pour créer les dossiers parents si besoin
    @mkdir($dossierMiniatures, 0775, true);
}

// Double-sécurité : on vérifie s'il est accessible en écriture
// Si ce n'est pas le cas (ou si mkdir a échoué), on tente de forcer les permissions
if (!is_writable($dossierMiniatures)) {
    // @chmod est utilisé pour ne pas générer de warning si ça échoue
    @chmod($dossierMiniatures, 0775);
}

// Test final : si après tout ça, on ne peut toujours pas écrire...
if (!is_writable($dossierMiniatures)) {
    // On arrête tout et on renvoie une erreur JSON propre
    // au lieu d'un plantage 500.
    header('Content-Type: application/json');
    // Indique au navigateur qu'il y a une erreur serveur
    http_response_code(500); 
    
    echo json_encode([
        'error' => 'Erreur critique: Les permissions du dossier "thumbs" sont incorrectes. 
                    Le script n\'a pas le droit d\'écrire dans ' . $dossierMiniatures . '. 
                    Veuillez régler les permissions de ce dossier sur 775 via FTP.'
    ]);
    exit; // Arrête l'exécution du script
}

// --- FIN DE LA VÉRIFICATION ---


// Fonction pour créer une miniature (version stable)
// Remplace cette fonction dans liste_images.php

// Remplace cette fonction dans liste_images.php

function creerMiniature($source, $destination, $largeurVoulue) {
    list($largeurSrc, $hauteurSrc, $type) = getimagesize($source);
    $hauteurVoulue = floor($hauteurSrc * ($largeurVoulue / $largeurSrc));

    $imgDestination = imagecreatetruecolor($largeurVoulue, $hauteurVoulue);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $imgSource = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $imgSource = imagecreatefrompng($source);
            // Préserver la transparence
            imagealphablending($imgDestination, false);
            imagesavealpha($imgDestination, true);
            $transparent = imagecolorallocatealpha($imgDestination, 255, 255, 255, 127);
            imagefilledrectangle($imgDestination, 0, 0, $largeurVoulue, $hauteurVoulue, $transparent);
            break;
        default:
            return false;
    }

    // MODIFIÉ : Redimensionnement simple. SANS filtre de netteté.
    imagecopyresampled($imgDestination, $imgSource, 0, 0, 0, 0, $largeurVoulue, $hauteurVoulue, $largeurSrc, $hauteurSrc);

    // Sauvegarde
    switch ($type) {
        case IMAGETYPE_JPEG:
            // MODIFIÉ: Qualité augmentée à 95 pour minimiser les artefacts
            imagejpeg($imgDestination, $destination, 95); 
            break;
        case IMAGETYPE_PNG:
            // La compression 6 est sans perte (lossless), la qualité est parfaite.
            imagepng($imgDestination, $destination, 6); 
            break;
    }

    imagedestroy($imgSource);
    imagedestroy($imgDestination);
    return true;
}


// --- LOGIQUE PRINCIPALE ---

$listeImagesFinales = []; // Le tableau à renvoyer
$fichiers = scandir($dossierOriginaux);
natsort($fichiers);

foreach ($fichiers as $fichier) {
    // Ignorer les dossiers '.' et '..' et le dossier des miniatures lui-même
    if ($fichier === '.' || $fichier === '..' || $fichier === 'thumbs') {
        continue;
    }
    
    $cheminOriginal = $dossierOriginaux . $fichier;
    $cheminMiniature = $dossierMiniatures . $fichier;
    
    // S'assurer que c'est un fichier et non un dossier
    if (is_file($cheminOriginal)) {
        
        // --- CORRECTION ---
        // On appelle getimagesize UNE SEULE FOIS et on stocke tout
        $info = @getimagesize($cheminOriginal);
        
        // Si getimagesize a échoué (fichier corrompu, pas une image)
        if ($info === false) {
            continue; // On ignore ce fichier
        }
        
        // On extrait les infos maintenant qu'on sait qu'elles existent
        $largeurSrc = $info[0];
        $hauteurSrc = $info[1];
        $type = $info[2];
        
        // On récupère la mémoire nécessaire (astuce)
        $memoryNeeded = $largeurSrc * $hauteurSrc * 4; // Approximation grossière
        $memoryLimit = 128 * 1024 * 1024; // Mettre ta limite (ex: 128M)
        
        // Si l'image est trop grosse
        if ($memoryNeeded > $memoryLimit) {
            // On ignore ce fichier et on passe au suivant
            continue; 
        }
        // --- FIN CORRECTION ---
        
        // On ne traite que les JPG et PNG
        if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
            
            // Si la miniature n'existe pas, on la crée
            if (!file_exists($cheminMiniature)) {
                // La vérification en haut garantit qu'on a le droit d'écrire ici
                creerMiniature($cheminOriginal, $cheminMiniature, $largeurMiniature);
            }
            
            // On ajoute l'image à notre liste
            $listeImagesFinales[] = [
                'thumb' => $cheminMiniature, // Ex: "images/thumbs/lapins1.jpg"
                'original' => $cheminOriginal // Ex: "images/lapins1.jpg"
            ];
        }
    }
}

// Renvoyer la liste au format JSON
header('Content-Type: application/json');
echo json_encode($listeImagesFinales);
?>