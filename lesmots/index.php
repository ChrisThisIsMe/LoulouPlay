<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apprendre les mots - Choisir une catégorie</title>
    <link rel="icon" type="image/png" href="img/mascotte-hibou.png">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Apprendre les mots - Jeu éducatif pour enfants">
    <meta property="og:description" content="Aidez votre enfant à apprendre du vocabulaire avec le hibou Loulou !">
    <meta property="og:image" content="https://loulouplay.com/img/mascotte-hibou-reseau-sociaux.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="https://loulouplay.com/lesmots/index.php">
    <meta property="og:site_name" content="LoulouPlay - Apprendre les mots">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Apprendre les mots - Jeu éducatif pour enfants">
    <meta name="twitter:description" content="Aidez votre enfant à apprendre du vocabulaire avec le hibou Loulou !">
    <meta name="twitter:image" content="https://loulouplay.com/img/mascotte-hibou.png">

    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Fredoka', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px; 
            text-align: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .conteneur-page {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .mascotte-conteneur {
            margin: 20px auto;
            max-width: 200px;
        }
        
        .mascotte-conteneur img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            animation: flotte 3s ease-in-out infinite;
        }
        
        @keyframes flotte {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .bulle-dialogue {
            background-color: white;
            border-radius: 20px;
            padding: 15px 20px;
            margin: 10px auto;
            max-width: 300px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .bulle-dialogue:after {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 15px solid white;
        }
        
        .bulle-dialogue p {
            margin: 0;
            color: #2C3E50;
            font-size: 1.2em;
            font-weight: 600;
        }
        
        h1 { 
            color: white; 
            font-size: 2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }
        
        .menu-conteneur { 
            max-width: 800px; 
            margin: 40px auto; 
            padding: 20px;
        }
        
        .categorie-bouton {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            color: white;
            padding: 25px 30px;
            margin: 15px 0;
            font-size: 1.5em;
            font-weight: 600;
            text-decoration: none;
            border-radius: 20px;
            text-transform: capitalize;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .categorie-bouton:hover { 
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }
        
        .categorie-bouton:active {
            transform: translateY(-2px) scale(0.98);
        }
        
        .emoji-icone {
            font-size: 2em;
            filter: drop-shadow(2px 2px 3px rgba(0,0,0,0.3));
        }
        
        /* Couleurs spécifiques pour chaque catégorie */
        .couleur-actions { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .couleur-aliments { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .couleur-animaux { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .couleur-concepts { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .couleur-corps { background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%); }
        .couleur-maison_objets { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
        .couleur-nature_lieux { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        .couleur-transports { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .couleur-vetements { background: linear-gradient(135deg, #c471f5 0%, #fa71cd 100%); }
        
        .message-vide {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            color: #2C3E50;
        }
    </style>
</head>
<body>
    <div class="conteneur-page">
        <?php include '../includes/nav.php'; ?>
            
        <div class="mascotte-conteneur">
            <img src="img/mascotte-hibou.png" alt="Mascotte Hibou">
        </div>
        
        <div class="bulle-dialogue">
            <p>🎉 Choisis une catégorie pour commencer !</p>
        </div>

        <div class="menu-conteneur">
            
            <?php
            // Chemin vers ton dossier de listes
            $chemin_csv = 'csv/';
            
            // Scanne le dossier pour trouver les fichiers .csv
            $fichiers_csv = glob($chemin_csv . '*.csv');
            
            // Associe des emojis et couleurs spécifiques à tes catégories
            $config_categories = [
                'actions' => ['emoji' => '🏃', 'couleur' => 'couleur-actions'],
                'aliments' => ['emoji' => '🍕', 'couleur' => 'couleur-aliments'],
                'animaux' => ['emoji' => '🦁', 'couleur' => 'couleur-animaux'],
                'concepts' => ['emoji' => '💡', 'couleur' => 'couleur-concepts'],
                'corps' => ['emoji' => '👁️', 'couleur' => 'couleur-corps'],
                'maison_objets' => ['emoji' => '🏠', 'couleur' => 'couleur-maison_objets'],
                'nature_lieux' => ['emoji' => '🌳', 'couleur' => 'couleur-nature_lieux'],
                'transports' => ['emoji' => '🚗', 'couleur' => 'couleur-transports'],
                'vetements' => ['emoji' => '👕', 'couleur' => 'couleur-vetements']
            ];
            
            if (empty($fichiers_csv)) {
                echo '<div class="message-vide">';
                echo "<p>Aucune catégorie (fichier .csv) n'a été trouvée dans le dossier 'csv/'.</p>";
                echo '</div>';
            } else {
                foreach ($fichiers_csv as $fichier) {
                    // Extrait le nom du fichier sans ".csv"
                    $nom_categorie = basename($fichier, '.csv');
                    
                    // Nettoie le nom pour l'affichage (remplace les underscores par des espaces)
                    $nom_affiche = ucfirst(str_replace('_', ' ', $nom_categorie));
                    
                    // Récupère la config pour cette catégorie ou utilise des valeurs par défaut
                    $config = $config_categories[$nom_categorie] ?? ['emoji' => '📖', 'couleur' => 'couleur-actions'];
                    $emoji = $config['emoji'];
                    $classe_couleur = $config['couleur'];
                    
                    // Crée le lien avec emoji et style personnalisé
                    echo '<a href="jeu.php?cat=' . urlencode($nom_categorie) . '" class="categorie-bouton ' . $classe_couleur . '">';
                    echo '<span class="emoji-icone">' . $emoji . '</span>';
                    echo '<span>' . htmlspecialchars($nom_affiche) . '</span>';
                    echo '</a>';
                }
            }
            ?>
        </div>

        <?php include '../includes/footer.php'; ?>
    </div>

</body>
</html>
