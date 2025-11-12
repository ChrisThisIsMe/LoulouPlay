<?php
/**
 * Page Contact - Loulou Play
 * Formulaire de contact sécurisé avec protection anti-spam et anti-injection
 */

// Chargement de la configuration
require_once 'config.php';

// Titre de la page
$page_title = 'Contact';

// Initialisation des variables
$nom = $email = $sujet = $message = '';
$errors = [];
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // === PROTECTION ANTI-BOT : Honeypot (champ caché) ===
    if (!empty($_POST['website'])) {
        // Si le champ "website" est rempli, c'est un bot
        die('Erreur de sécurité détectée.');
    }
    
    // === PROTECTION ANTI-BOT : Time trap ===
    $form_start_time = isset($_POST['form_start_time']) ? intval($_POST['form_start_time']) : 0;
    $current_time = time();
    if (($current_time - $form_start_time) < 3) {
        // Si le formulaire a été soumis en moins de 3 secondes, c'est un bot
        $errors[] = "Le formulaire a été soumis trop rapidement.";
    }
    
    // Récupération et nettoyage des données
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $sujet = isset($_POST['sujet']) ? trim($_POST['sujet']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // === VALIDATIONS ===
    
    // Validation du nom
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    } elseif (strlen($nom) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    } elseif (strlen($nom) > 100) {
        $errors[] = "Le nom ne peut pas dépasser 100 caractères.";
    }
    
    // Validation de l'email
    if (empty($email)) {
        $errors[] = "L'adresse email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    } elseif (strlen($email) > 255) {
        $errors[] = "L'adresse email est trop longue.";
    }
    
    // Validation du sujet
    if (empty($sujet)) {
        $errors[] = "Le sujet est obligatoire.";
    } elseif (strlen($sujet) < 3) {
        $errors[] = "Le sujet doit contenir au moins 3 caractères.";
    } elseif (strlen($sujet) > 200) {
        $errors[] = "Le sujet ne peut pas dépasser 200 caractères.";
    }
    
    // Validation du message
    if (empty($message)) {
        $errors[] = "Le message est obligatoire.";
    } elseif (strlen($message) < 10) {
        $errors[] = "Le message doit contenir au moins 10 caractères.";
    } elseif (strlen($message) > 5000) {
        $errors[] = "Le message ne peut pas dépasser 5000 caractères.";
    }
    
    // === PROTECTION ANTI-INJECTION ===
    // Détection de headers malveillants dans tous les champs
    $suspicious_patterns = [
        '/content-type:/i',
        '/mime-version:/i',
        '/content-transfer-encoding:/i',
        '/bcc:/i',
        '/cc:/i',
        '/to:/i',
        '/from:/i',
        '/subject:/i',
        '/<script/i',
        '/javascript:/i',
        '/onclick/i',
        '/onerror/i'
    ];
    
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $nom) || preg_match($pattern, $email) || 
            preg_match($pattern, $sujet) || preg_match($pattern, $message)) {
            $errors[] = "Contenu suspect détecté. Votre message n'a pas pu être envoyé.";
            break;
        }
    }
    
    // Vérification du nombre de liens dans le message (protection anti-spam)
    $link_count = preg_match_all('/(http|https|ftp):\/\//i', $message);
    if ($link_count > 3) {
        $errors[] = "Le message contient trop de liens (maximum 3 autorisés).";
    }
    
    // === ENVOI DE L'EMAIL SI AUCUNE ERREUR ===
    if (empty($errors)) {
        
        // Email de destination (FIXE et NON modifiable par l'utilisateur)
        $to = 'christophe.siegenthaler@gmail.com';
        
        // Nettoyage supplémentaire pour éviter les injections dans les headers
        $nom_clean = str_replace(["\r", "\n", "%0a", "%0d"], '', $nom);
        $email_clean = str_replace(["\r", "\n", "%0a", "%0d"], '', $email);
        $sujet_clean = str_replace(["\r", "\n", "%0a", "%0d"], '', $sujet);
        
        // Préparation du sujet de l'email
        $email_subject = '[Loulou Play] ' . $sujet_clean;
        
        // Préparation du corps de l'email
        $email_body = "Nouveau message depuis le formulaire de contact de Loulou Play\n\n";
        $email_body .= "Nom : " . $nom_clean . "\n";
        $email_body .= "Email : " . $email_clean . "\n";
        $email_body .= "Sujet : " . $sujet_clean . "\n\n";
        $email_body .= "Message :\n" . $message . "\n\n";
        $email_body .= "---\n";
        $email_body .= "Envoyé le : " . date('d/m/Y à H:i:s') . "\n";
        $email_body .= "IP : " . $_SERVER['REMOTE_ADDR'] . "\n";
        
        // Headers de l'email (sécurisés)
        $headers = "From: noreply@loulouplay.com\r\n";
        $headers .= "Reply-To: " . $email_clean . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Envoi de l'email
        if (mail($to, $email_subject, $email_body, $headers)) {
            $success = true;
            // Réinitialisation des champs après envoi réussi
            $nom = $email = $sujet = $message = '';
        } else {
            $errors[] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
        }
    }
}

// Génération du timestamp pour le time trap
$form_timestamp = time();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez l'équipe de Loulou Play - Vos questions, suggestions et retours sont les bienvenus !">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">

    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo PATH_IMG; ?>mascotte-hibou.png">
    <link rel="stylesheet" href="<?php echo PATH_ROOT; ?>includes/common-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
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
        }
        
        .site-header {
            text-align: center;
            padding: 0 20px 20px;
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .site-header h1 {
            font-size: 4em;
            font-weight: 700;
            color: #FF6B35;
            text-shadow: 
                4px 4px 0px rgba(255, 255, 255, 0.9),
                -2px -2px 0px rgba(255, 255, 255, 0.5);
            margin-bottom: 10px;
        }
        
        .mascotte-dialogue {
            max-width: 600px;
            margin: 30px auto;
            text-align: center;
        }
        
        .mascotte-dialogue img {
            width: 150px;
            height: auto;
            animation: flotte 3s ease-in-out infinite;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2));
        }
        
        @keyframes flotte {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }
        
        .bulle-dialogue {
            background: white;
            border-radius: 25px;
            padding: 20px 30px;
            margin-top: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            position: relative;
        }
        
        .bulle-dialogue:before {
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
            color: #2C3E50;
            font-size: 1.3em;
            font-weight: 600;
            line-height: 1.6;
        }
        
        .conteneur-principal {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .conteneur-principal h2 {
            color: #5E35B1;
            font-size: 2.5em;
            margin-bottom: 15px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .conteneur-principal .sous-titre {
            text-align: center;
            color: #667eea;
            font-size: 1.3em;
            margin-bottom: 40px;
            font-weight: 600;
        }
        
        .intro-text {
            color: #2C3E50;
            font-size: 1.15em;
            line-height: 1.8;
            margin-bottom: 30px;
            text-align: center;
        }
        
        /* === MESSAGES D'ERREUR ET SUCCÈS === */
        .message-box {
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .message-success {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            border-left: 6px solid #66BB6A;
            color: #2E7D32;
        }
        
        .message-success strong {
            color: #1B5E20;
        }
        
        .message-error {
            background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
            border-left: 6px solid #EF5350;
            color: #C62828;
        }
        
        .message-error ul {
            margin: 10px 0 0 20px;
        }
        
        .message-error li {
            margin-bottom: 5px;
        }
        
        /* === FORMULAIRE === */
        .formulaire-contact {
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            color: #2C3E50;
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-group label .required {
            color: #E74C3C;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            font-family: 'Fredoka', Arial, sans-serif;
            font-size: 1.1em;
            border: 3px solid #E8EAF6;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            color: #2C3E50;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 200px;
        }
        
        .caracteres-restants {
            text-align: right;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        /* Champ honeypot (caché) */
        .honeypot {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            opacity: 0;
        }
        
        .btn-submit {
            display: block;
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-family: 'Fredoka', Arial, sans-serif;
            font-size: 1.4em;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            margin-top: 30px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
        
        .btn-submit:active {
            transform: translateY(-1px);
        }
        
        @media (max-width: 750px) {
            body {
                padding: 15px;
            }
            
            .site-header h1 {
                font-size: 2.5em;
            }
            
            .mascotte-dialogue img {
                width: 120px;
                height: auto;
            }
            
            .bulle-dialogue p {
                font-size: 1.1em;
            }
            
            .conteneur-principal {
                padding: 30px 20px;
            }
            
            .conteneur-principal h2 {
                font-size: 1.9em;
            }
            
            .conteneur-principal .sous-titre {
                font-size: 1.1em;
            }
            
            .intro-text {
                font-size: 1.05em;
            }
            
            .form-group label {
                font-size: 1.1em;
            }
            
            .form-group input,
            .form-group textarea {
                font-size: 1em;
                padding: 12px 15px;
            }
            
            .btn-submit {
                font-size: 1.2em;
                padding: 18px;
            }
        }
    </style>
</head>
<body>

    <!-- Mascotte flottante -->
    <img src="<?php echo PATH_IMG; ?>mascotte-hibou.png" alt="Hibou Loulou" class="mascotte-flottante">

    <!-- Header -->
    <header class="site-header">
        <!-- Navigation -->
        <?php include 'includes/nav.php'; ?>
        <h1>loulou play</h1>
        
        <!-- Mascotte avec bulle -->
        <div class="mascotte-dialogue">
            <img src="<?php echo PATH_IMG; ?>mascotte-hibou.png" alt="Hibou Loulou">
            <div class="bulle-dialogue">
                <p>💬 Vous avez une question ? Une suggestion ? Je suis là pour vous écouter !</p>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="conteneur-principal">
        <h2>📧 Nous contacter</h2>
        <p class="sous-titre">Vos retours sont précieux</p>
        
        <p class="intro-text">
            Que ce soit pour partager votre expérience, poser une question, signaler un problème ou proposer une amélioration, n'hésitez pas à nous écrire. Nous lisons chaque message avec attention et vous répondons dans les plus brefs délais.
        </p>

        <?php if ($success): ?>
            <div class="message-box message-success">
                <strong>✅ Message envoyé avec succès !</strong><br>
                Merci pour votre message. Nous vous répondrons dans les plus brefs délais à l'adresse email que vous avez indiquée.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="message-box message-error">
                <strong>⚠️ Erreur(s) détectée(s) :</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulaire de contact -->
        <form method="POST" action="" class="formulaire-contact" id="contactForm">
            
            <!-- Champ honeypot (piège à bots) -->
            <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">
            
            <!-- Champ time trap -->
            <input type="hidden" name="form_start_time" value="<?php echo $form_timestamp; ?>">
            
            <!-- Nom -->
            <div class="form-group">
                <label for="nom">
                    Votre nom <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    value="<?php echo htmlspecialchars($nom); ?>"
                    required 
                    maxlength="100"
                    placeholder="Prénom et nom">
            </div>
            
            <!-- Email -->
            <div class="form-group">
                <label for="email">
                    Votre email <span class="required">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($email); ?>"
                    required 
                    maxlength="255"
                    placeholder="votre.email@exemple.com">
            </div>
            
            <!-- Sujet -->
            <div class="form-group">
                <label for="sujet">
                    Sujet <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="sujet" 
                    name="sujet" 
                    value="<?php echo htmlspecialchars($sujet); ?>"
                    required 
                    maxlength="200"
                    placeholder="Ex: Question sur les jeux, Suggestion d'amélioration...">
            </div>
            
            <!-- Message -->
            <div class="form-group">
                <label for="message">
                    Votre message <span class="required">*</span>
                </label>
                <textarea 
                    id="message" 
                    name="message" 
                    required 
                    maxlength="5000"
                    placeholder="Écrivez votre message ici..."><?php echo htmlspecialchars($message); ?></textarea>
                <div class="caracteres-restants">
                    <span id="charCount">0</span> / 5000 caractères
                </div>
            </div>
            
            <!-- Bouton d'envoi -->
            <button type="submit" class="btn-submit">
                🚀 Envoyer le message
            </button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Compteur de caractères pour le message
        const messageField = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        
        function updateCharCount() {
            const length = messageField.value.length;
            charCount.textContent = length;
            
            if (length > 4800) {
                charCount.style.color = '#E74C3C';
            } else if (length > 4500) {
                charCount.style.color = '#FF9800';
            } else {
                charCount.style.color = '#7f8c8d';
            }
        }
        
        messageField.addEventListener('input', updateCharCount);
        
        // Initialisation du compteur au chargement
        updateCharCount();
        
        // Confirmation avant envoi
        const form = document.getElementById('contactForm');
        form.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom').value.trim();
            const email = document.getElementById('email').value.trim();
            const sujet = document.getElementById('sujet').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!nom || !email || !sujet || !message) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    </script>

</body>
</html>
