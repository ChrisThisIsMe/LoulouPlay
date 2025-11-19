<?php
// ATTENTION : CODE TEMPORAIRE À ENLEVER LORS DE LA MISE EN PROD !
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// === CHARGER LA CONFIGURATION ===
require_once '../config.php';

// === ÉTAPE 0 : RÉCUPÉRER LA CATÉGORIE ===
if (empty($_GET['cat'])) {
    die("Erreur : Aucune catégorie n'a été sélectionnée. <a href='index.php'>Retour au menu</a>");
}
$categorie = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['cat']);
$nom_affiche = ucfirst(str_replace('_', ' ', $categorie));
$fichier_map = 'csv/' . $categorie . '.csv';
$chemin_base_images = 'img/' . $categorie . '/';

if (!file_exists($fichier_map)) {
    die("Erreur : Le fichier CSV pour la catégorie '" . htmlspecialchars($categorie) . "' est introuvable. <a href='index.php'>Retour au menu</a>");
}
if (!is_dir($chemin_base_images)) {
    die("Erreur : Le dossier d'images pour la catégorie '" . htmlspecialchars($categorie) . "' est introuvable. <a href='index.php'>Retour au menu</a>");
}

// === ÉTAPE 1 : DÉMARRER LA SESSION ===
session_start();

// === CONFIGURATION DU MODE DEBUG ===
// On vérifie si la constante globale est définie, sinon on met à false par défaut.
// Tu peux mettre cette ligne à "true" manuellement ici si tu veux forcer le debug
$MODE_DEBUG = defined('DEBUG_MODE') ? DEBUG_MODE : false; 
// $MODE_DEBUG = true; // Décommente cette ligne pour forcer le mode debug temporairement

// === CODE DE NETTOYAGE FORCÉ (Uniquement en DEBUG) ===
// Si on clique sur le lien de reset ET qu'on est en debug
if ($MODE_DEBUG && isset($_GET['reset_cache'])) {
    unset($_SESSION['paquet_' . $categorie]);
    unset($_SESSION['index_' . $categorie]);
    unset($_SESSION['timestamp_' . $categorie]);
    // On recharge la page proprement sans le paramètre reset_cache
    header("Location: jeu.php?cat=" . $categorie);
    exit;
}

// --- Fonction pour construire et mélanger le paquet ---
function creer_paquet_melange($repetitions, $fichier_map, $chemin_base_images) {
    $mots_de_base = [];

    if (file_exists($fichier_map) && is_readable($fichier_map)) {
        if (($handle = fopen($fichier_map, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if (count($data) >= 2) {
                    $nom_fichier = trim($data[0]);
                    $mot_correct = trim($data[1]);
                    $syllabes = (isset($data[2]) && !empty(trim($data[2]))) ? trim($data[2]) : '';
                    $chemin_complet = $chemin_base_images . $nom_fichier;
                    
                    if (file_exists($chemin_complet)) {
                        $mots_de_base[] = [
                            'image'    => $nom_fichier,
                            'mot'      => $mot_correct,
                            'syllabes' => $syllabes
                        ];
                    }
                }
            }
            fclose($handle);
        }
    }
    
    if (empty($mots_de_base)) { return []; }

    $paquet_complet = [];
    for ($i = 0; $i < $repetitions; $i++) {
        $paquet_complet = array_merge($paquet_complet, $mots_de_base);
    }
    
    shuffle($paquet_complet);
    return $paquet_complet;
}

// === ÉTAPE 2 : GESTION DE LA SESSION ET DU HOT-RELOAD ===
$cle_paquet = 'paquet_' . $categorie;
$cle_index = 'index_' . $categorie;
$cle_dernier_mot = 'dernier_mot_' . $categorie;
$cle_timestamp = 'timestamp_' . $categorie;

// On récupère la date de dernière modification du fichier CSV
$date_modif_csv = file_exists($fichier_map) ? filemtime($fichier_map) : 0;

$forcer_rechargement = false;

// Hot-reload automatique
if (isset($_SESSION[$cle_timestamp]) && $_SESSION[$cle_timestamp] < $date_modif_csv) {
    $forcer_rechargement = true;
    if ($MODE_DEBUG) echo "<div style='position:fixed;top:0;left:0;background:red;color:white;padding:5px;z-index:9999'>CSV Modifié ! Rechargement...</div>";
}

if ($forcer_rechargement || empty($_SESSION[$cle_paquet]) || !isset($_SESSION[$cle_index]) || $_SESSION[$cle_index] >= count($_SESSION[$cle_paquet])) {
    
    $repetitions_par_categorie = 5;
    
    $_SESSION[$cle_paquet] = creer_paquet_melange($repetitions_par_categorie, $fichier_map, $chemin_base_images); 
    $_SESSION[$cle_index] = 0;
    $_SESSION[$cle_dernier_mot] = null; 
    
    $_SESSION[$cle_timestamp] = $date_modif_csv;
}

// === MODE DEBUG : Recherche de mot spécifique ===
if ($MODE_DEBUG && !empty($_GET['debug_mot'])) {
    $mot_recherche = trim($_GET['debug_mot']);
    $index_trouve = null;
    
    foreach ($_SESSION[$cle_paquet] as $index => $item) {
        if (strtolower($item['mot']) === strtolower($mot_recherche)) {
            $index_trouve = $index;
            break;
        }
    }
    
    if ($index_trouve !== null) {
        $_SESSION[$cle_index] = $index_trouve;
        $_SESSION[$cle_dernier_mot] = null;
    }
}

// === ÉTAPE 3 : SÉLECTION DU MOT ACTUEL ===
if (empty($_SESSION[$cle_paquet])) {
    $mot = "ERREUR";
    $image_fichier = "";
    $mot_a_vocaliser = "Erreur, aucun mot valide dans cette catégorie";
    $syllabes_a_vocaliser = ""; 
    $chemin_image = "";
} else {
    $index_actuel = $_SESSION[$cle_index];
    $mot_actuel = $_SESSION[$cle_paquet][$index_actuel];
    $dernier_mot_vu = $_SESSION[$cle_dernier_mot] ?? null;

    if ($dernier_mot_vu !== null && $mot_actuel['image'] == $dernier_mot_vu) {
        $index_suivant = $index_actuel + 1;
        if ($index_suivant < count($_SESSION[$cle_paquet])) {
            $mot_a_echanger = $_SESSION[$cle_paquet][$index_suivant];
            $_SESSION[$cle_paquet][$index_actuel] = $mot_a_echanger;
            $_SESSION[$cle_paquet][$index_suivant] = $mot_actuel;
            $mot_actuel = $mot_a_echanger;
        }
    }

    $mot = $mot_actuel['mot'];
    $image_fichier = $mot_actuel['image']; 
    $chemin_image = $chemin_base_images . $image_fichier;
    $mot_a_vocaliser = strtolower($mot);
    $syllabes_a_vocaliser = $mot_actuel['syllabes'];
    
    $_SESSION[$cle_dernier_mot] = $mot_actuel['image'];
    $_SESSION[$cle_index]++;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entraînement - <?php echo $nom_affiche; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../includes/common-styles.css">
    <style>
        body { 
            font-family: 'Fredoka', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .conteneur { 
            max-width: 700px; 
            margin: 20px auto; 
            background-color: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        
        h1 { 
            color: #2C3E50; 
            font-size: 2.2em; 
            font-weight: 600;
            text-transform: capitalize;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .image-container { 
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .image-mot { 
            max-width: 100%; 
            height: auto; 
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        
        .texte-mot { 
            font-size: 3.5em; 
            color: #E74C3C; 
            margin: 30px 0;
            letter-spacing: 8px; 
            text-transform: uppercase; 
            word-wrap: break-word;
            font-weight: 600;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.2);
            text-align: center;
        }
        
        .bouton-actions { 
            display: flex; 
            flex-wrap: wrap; 
            justify-content: center; 
            gap: 15px;
            margin-top: 30px;
        }
        
        .bouton-actions button, .lien-menu {
            padding: 18px 30px;
            font-size: 1.2em;
            font-weight: 600;
            font-family: 'Fredoka', Arial, sans-serif;
            cursor: pointer;
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            flex-grow: 1;
            flex-basis: 180px;
            text-decoration: none;
            box-sizing: border-box;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: inline-block;
            text-align: center;
        }
        
        .vocaliser { 
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        
        .suivant { 
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        
        .syllabes { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .lien-menu { 
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #2C3E50;
        }

        .vocaliser:hover, .suivant:hover, .syllabes:hover, .lien-menu:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .vocaliser:active, .suivant:active, .syllabes:active, .lien-menu:active {
            transform: translateY(-1px) scale(0.98);
        }

        .message-erreur {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border: 3px solid #ff9a56;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            color: #d46b08;
            text-align: left;
            line-height: 1.8;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .message-erreur code {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: bold;
            font-family: monospace;
        }
        
        @media (max-width: 750px) {
            body {
                padding: 15px;
            }
            
            .conteneur { 
                margin: 20px auto; 
                padding: 25px 20px; 
            }
            h1 { 
                font-size: 1.8em; 
            }
            .image-container { 
                margin: 20px 0; 
                padding: 15px; 
            }
            .texte-mot { 
                font-size: 2.5em; 
                letter-spacing: 4px; 
                margin: 20px 0; 
            }
            .bouton-actions button, .lien-menu {
                font-size: 1em;
                padding: 15px 20px;
                flex-basis: 100%;
            }
        }
        
        /* CSS POUR LE MODE DEBUG */
        .debug-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            z-index: 2000;
            min-width: 300px;
        }
        
        .debug-modal.active {
            display: block;
        }
        
        .debug-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 1999;
        }
        
        .debug-overlay.active {
            display: block;
        }
        
        .debug-modal input {
            width: 100%;
            padding: 12px;
            font-size: 1.1em;
            border: 2px solid #667eea;
            border-radius: 8px;
            margin: 15px 0;
            box-sizing: border-box;
            font-family: 'Fredoka', Arial, sans-serif;
        }
        
        .debug-modal button {
            padding: 12px 25px;
            margin: 5px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            border: none;
            border-radius: 10px;
            font-family: 'Fredoka', Arial, sans-serif;
        }
        
        .debug-modal .btn-valider {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        
        .debug-modal .btn-annuler {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .debug-modal h3 {
            margin: 0 0 10px 0;
            color: #2C3E50;
            font-size: 1.3em;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <?php include '../includes/nav.php'; ?>
    
    <img src="../img/mascotte-hibou.png" alt="Hibou Loulou" class="mascotte-flottante">

    <div class="conteneur">
        <h1>🎯 <?php echo htmlspecialchars($nom_affiche); ?></h1>
    
        <?php if ($mot === "ERREUR"): ?>
            
            <div class="message-erreur">
                <p><strong>⚠️ Aucun mot n'a pu être chargé.</strong></p>
                <p>Cela signifie qu'aucune image correspondante n'a été trouvée dans le dossier de cette catégorie.</p>
                <p>Veuillez ajouter des images dans le dossier :<br>
                <code>img/<?php echo htmlspecialchars($categorie); ?>/</code></p>
            </div>
    
        <?php else: ?>
    
            <div class="image-container">
                <img src="<?php echo $chemin_image; ?>" alt="Image du mot" class="image-mot">
            </div>
            
            <?php if ($MODE_DEBUG): ?>
                <div style="background:yellow; color:black; padding:10px; font-weight:bold; text-align:center; border-radius:10px; margin-bottom:10px;">
                    🔧 DEBUG Syllabes : [ <?php echo $syllabes_a_vocaliser; ?> ]
                </div>
            <?php endif; ?>

            <div class="texte-mot">
                <?php echo htmlspecialchars($mot); ?>
            </div>
    
        <?php endif; ?>
    
        <div class="bouton-actions">
            
            <?php if ($mot !== "ERREUR"): ?>
                <button class="vocaliser" onclick="vocaliser('<?php echo htmlspecialchars($mot_a_vocaliser); ?>')">
                    🔊 Écouter le Mot
                </button>
                <?php if (!empty($syllabes_a_vocaliser)): ?>
                    <button class="syllabes" onclick="vocaliserSyllabes('<?php echo htmlspecialchars($syllabes_a_vocaliser); ?>')">
                        📖 Écouter les Syllabes
                    </button>
                <?php endif; ?>
                <button class="suivant" onclick="window.location.reload();">
                    🎲 Mot Suivant
                </button>
            <?php endif; ?>
            
            <a href="index.php" class="lien-menu">🏠 Retour au Menu</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.speechSynthesis.onvoiceschanged = function () {
                if (!('speechSynthesis' in window)) {
                    // alert('La synthèse vocale n\'est pas disponible sur ce navigateur. Veuillez privilégier Google Chrome ou Microsoft Edge.');
                } else {
                    var voices = window.speechSynthesis.getVoices();
                    if (voices.length === 0) {
                        // alert('La synthèse vocale est inactive ou sans voix sur ce navigateur. Veuillez privilégier Google Chrome ou Microsoft Edge.');
                    }
                }
            };
        });
    
        function vocaliser(texte) {
            window.speechSynthesis.cancel(); 
            if ('speechSynthesis' in window) {
                var utterance = new SpeechSynthesisUtterance(texte);
                utterance.lang = 'fr-FR'; 
                utterance.rate = 0.85;
                window.speechSynthesis.speak(utterance);
            } else {
                alert("Désolé, la synthèse vocale n'est pas supportée par votre navigateur.");
            }
        }
    
        function corrigerPhoneme(syllabe) {
            var s = syllabe.toLowerCase();
            if (s === 'é') { return 'et'; }
            if (s === 'è' || s === 'ê') { return 'è'; }
            if (s === 'a' || s === 'à' || s === 'â') { return 'a'; }
            if (s === 'o' || s === 'ô') { return 'au'; }
            if (s === 'i' || s === 'î') { return 'y'; }
            if (s === 'u' || s === 'ù' || s === 'û') { return 'u'; }
            var voyelles = ['e','é','è','ê','a','à','â','o','ô','i','î','u','ù','û'];
            if (voyelles.indexOf(s) === -1) { return s + 'e'; }
            return s;
        }
    
        function vocaliserSyllabes(texteSyllabes) {
            if (!texteSyllabes || !'speechSynthesis' in window) { return; }
            window.speechSynthesis.cancel(); 
            var syllabes = texteSyllabes.split(','); 
            var index = 0;
            function parlerProchaineSyllabe() {
                if (index >= syllabes.length) { return; }
                var syllabeActuelle = syllabes[index];
    
                if (syllabeActuelle.toLowerCase() === 'gnée') { syllabeActuelle = 'nié'; }
                if (syllabeActuelle.toLowerCase() === 'ko') { syllabeActuelle = 'ko'; }
                if (syllabeActuelle.toLowerCase() === 'co' && texteSyllabes.includes('bri')) { syllabeActuelle = 'cô'; } 
    
                if (syllabeActuelle.length === 1) { syllabeActuelle = corrigerPhoneme(syllabeActuelle); }
                
                var utterance = new SpeechSynthesisUtterance(syllabeActuelle);
                utterance.lang = 'fr-FR';
                if (syllabeActuelle.length <= 2) { utterance.rate = 0.7; } else { utterance.rate = 0.8; }
                utterance.onend = function() { index++; parlerProchaineSyllabe(); };
                window.speechSynthesis.speak(utterance);
            }
            parlerProchaineSyllabe();
        }
        
        <?php if ($MODE_DEBUG): ?>
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'F') {
                e.preventDefault();
                document.getElementById('debugModal').classList.add('active');
                document.getElementById('debugOverlay').classList.add('active');
                document.getElementById('debugMotInput').focus();
            }
        });
        
        document.getElementById('debugMotInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                chargerMotDebug();
            }
        });
        
        function chargerMotDebug() {
            var mot = document.getElementById('debugMotInput').value.trim();
            if (mot) {
                window.location.href = '?cat=<?php echo $categorie; ?>&debug_mot=' + encodeURIComponent(mot);
            }
        }
        
        function fermerDebugModal() {
            document.getElementById('debugModal').classList.remove('active');
            document.getElementById('debugOverlay').classList.remove('active');
            document.getElementById('debugMotInput').value = '';
        }
        <?php endif; ?>
    </script>

    <?php if ($MODE_DEBUG): ?>
    <a href="?cat=<?php echo $categorie; ?>&reset_cache=1" class="bouton-actions" style="background: #ff4757; color: white; text-decoration:none; padding: 18px 30px; border-radius: 15px; display:inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.2); margin-top: 20px;">
        🔄 RECHARGER CSV
    </a>
    
    <div class="debug-overlay" id="debugOverlay" onclick="fermerDebugModal()"></div>
    <div class="debug-modal" id="debugModal">
        <h3>🔧 Mode Développeur</h3>
        <p>Entrez le mot à tester :</p>
        <input type="text" id="debugMotInput" placeholder="ex: laver">
        <div>
            <button class="btn-valider" onclick="chargerMotDebug()">✓ Valider</button>
            <button class="btn-annuler" onclick="fermerDebugModal()">✗ Annuler</button>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>