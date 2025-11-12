<?php
/**
 * Page CGU (Conditions Générales d'Utilisation) - Loulou Play
 */

// Chargement de la configuration
require_once 'config.php';

// Titre de la page
$page_title = 'Conditions Générales d\'Utilisation';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Conditions générales d'utilisation de Loulou Play - Un site éducatif gratuit, transparent et sans publicité pour accompagner votre enfant.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo PATH_IMG; ?>mascotte-hibou.png">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Fredoka', Arial, sans-serif; 
            background: linear-gradient(180deg, #667eea 0%, #C5CAE9 100%);
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
            max-width: 1200px;
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
        
        .section-cgu {
            margin-bottom: 50px;
        }
        
        .section-cgu h3 {
            color: #667eea;
            font-size: 1.9em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-cgu h3 .emoji {
            font-size: 1.2em;
        }
        
        .section-cgu p {
            color: #2C3E50;
            font-size: 1.15em;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .section-cgu ul {
            margin: 15px 0 15px 30px;
        }
        
        .section-cgu li {
            color: #2C3E50;
            font-size: 1.15em;
            line-height: 1.8;
            margin-bottom: 10px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            padding: 25px 30px;
            border-radius: 20px;
            margin: 25px 0;
            border-left: 6px solid #66BB6A;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .highlight-box.warning {
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            border-left-color: #FF9800;
        }
        
        .highlight-box.info {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            border-left-color: #2196F3;
        }
        
        .highlight-box p {
            margin-bottom: 10px;
        }
        
        .highlight-box p:last-child {
            margin-bottom: 0;
        }
        
        .date-maj {
            text-align: center;
            color: #7f8c8d;
            font-size: 1em;
            margin-top: 50px;
            font-style: italic;
        }
        
        .btn-contact {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            font-size: 1.3em;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            margin: 20px auto;
            display: block;
            text-align: center;
            max-width: 300px;
        }
        
        .btn-contact:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
        
        .mascotte-flottante {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 80px;
            height: auto;
            animation: flotte-coin 4s ease-in-out infinite;
            filter: drop-shadow(0 6px 15px rgba(0,0,0,0.3));
            z-index: 1000;
        }
        
        @keyframes flotte-coin {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }
        
        strong {
            color: #5E35B1;
            font-weight: 700;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        @media (max-width: 1024px) {
            .mascotte-flottante {
                width: 70px;
                height: auto;
            }
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
            
            .section-cgu h3 {
                font-size: 1.5em;
            }
            
            .section-cgu p,
            .section-cgu li {
                font-size: 1.05em;
            }
            
            .highlight-box {
                padding: 20px;
            }
            
            .btn-contact {
                font-size: 1.1em;
                padding: 15px 30px;
            }
            
            .mascotte-flottante {
                width: 55px;
                height: auto;
                bottom: 10px;
                right: 10px;
            }
        }
        
        @media (max-width: 400px) {
            .mascotte-flottante {
                width: 45px;
                height: auto;
                bottom: 8px;
                right: 8px;
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
                <p>🤝 Bienvenue ! Voici nos engagements envers vous, expliqués simplement et en toute transparence.</p>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="conteneur-principal">
        <h2>📜 Utilisation du site</h2>
        <p class="sous-titre">Simple et transparent</p>

        <!-- Section 1 : Qui sommes-nous -->
        <section class="section-cgu">
            <h3><span class="emoji">🦉</span> Qui sommes-nous ?</h3>
            <p>Loulou Play est un site éducatif créé par un papa développeur pour aider sa fille présentant un retard de langage. Ce projet personnel est supervisé par des experts (orthophonistes, éducateurs spécialisés) et mis à disposition gratuitement pour toutes les familles.</p>
            <p>Pour en savoir plus sur notre histoire et notre mission, rendez-vous sur notre page <a href="qui-sommes-nous.php">Qui sommes-nous</a>.</p>
        </section>

        <!-- Section 2 : Gratuité -->
        <section class="section-cgu">
            <h3><span class="emoji">💚</span> Gratuité totale et absence de publicité</h3>
            <div class="highlight-box">
                <p><strong>Notre engagement :</strong></p>
                <ul>
                    <li>✅ Ce site est et restera <strong>100% gratuit</strong></li>
                    <li>✅ Aucune publicité, jamais</li>
                    <li>✅ Aucun contenu sponsorisé</li>
                    <li>✅ Aucun achat in-app ou abonnement caché</li>
                    <li>✅ Aucune inscription ou création de compte requise</li>
                </ul>
            </div>
            <p>Loulou Play est un projet porté par la passion d'aider les enfants et leurs familles, pas par des intérêts commerciaux.</p>
        </section>

        <!-- Section 3 : Utilisation du site -->
        <section class="section-cgu">
            <h3><span class="emoji">👨‍👧‍👦</span> Utilisation responsable</h3>
            
            <div class="highlight-box warning">
                <p><strong>⚠️ Important : Accompagnement parental obligatoire</strong></p>
                <p>Les activités de ce site sont conçues pour être utilisées <strong>avec vous</strong>, en votre présence. Ne laissez jamais votre enfant seul devant l'écran. L'apprentissage du langage est un moment de partage privilégié entre vous et votre enfant.</p>
            </div>
            
            <p><strong>Conditions d'utilisation :</strong></p>
            <ul>
                <li><strong>Âge recommandé :</strong> 2 à 6 ans</li>
                <li><strong>Présence parentale :</strong> Obligatoire pendant toute l'utilisation</li>
                <li><strong>Durée d'utilisation :</strong> Sessions courtes recommandées (15-20 minutes maximum)</li>
                <li><strong>Complément, pas remplacement :</strong> Ce site ne remplace pas un suivi professionnel par un orthophoniste ou un éducateur spécialisé</li>
            </ul>
            
            <p>Si votre enfant présente des difficultés de langage, nous vous encourageons vivement à consulter un professionnel de santé qualifié.</p>
        </section>

        <!-- Section 4 : Propriété intellectuelle -->
        <section class="section-cgu">
            <h3><span class="emoji">©️</span> Propriété intellectuelle</h3>
            <p>L'ensemble des contenus présents sur ce site (jeux, exercices, images, textes, design) sont la propriété exclusive de Loulou Play ou de leurs auteurs respectifs.</p>
            <p><strong>Vous êtes autorisé à :</strong></p>
            <ul>
                <li>✅ Utiliser le site gratuitement pour un usage personnel et familial</li>
                <li>✅ Recommander le site à d'autres familles</li>
            </ul>
            <p><strong>Vous n'êtes pas autorisé à :</strong></p>
            <ul>
                <li>❌ Reproduire, copier ou redistribuer les contenus à des fins commerciales</li>
                <li>❌ Modifier ou créer des œuvres dérivées sans autorisation</li>
                <li>❌ Utiliser les contenus dans un cadre professionnel rémunéré sans accord préalable</li>
            </ul>
            <p>Pour toute demande d'utilisation particulière, merci de nous contacter.</p>
        </section>

        <!-- Section 5 : Protection des données -->
        <section class="section-cgu">
            <h3><span class="emoji">🔒</span> Protection de vos données (RGPD)</h3>
            
            <div class="highlight-box info">
                <p><strong>🛡️ Votre vie privée est notre priorité</strong></p>
                <p>Nous collectons le strict minimum d'informations nécessaires au bon fonctionnement du site.</p>
            </div>
            
            <p><strong>Ce que nous NE faisons PAS :</strong></p>
            <ul>
                <li>❌ Aucune collecte de données personnelles identifiantes</li>
                <li>❌ Aucun compte utilisateur ou inscription</li>
                <li>❌ Aucun tracking publicitaire ou comportemental</li>
                <li>❌ Aucune revente de données à des tiers</li>
                <li>❌ Aucun partage d'informations avec des partenaires commerciaux</li>
            </ul>
            
            <p><strong>Ce que nous utilisons :</strong></p>
            <ul>
                <li>✅ <strong>Cookies techniques uniquement :</strong> Nécessaires au fonctionnement du site (sessions PHP pour mémoriser votre progression dans un jeu pendant votre visite)</li>
                <li>✅ Ces cookies sont automatiquement supprimés lorsque vous fermez votre navigateur</li>
            </ul>
            
            <p><strong>Bandeau cookies :</strong> Conformément à la réglementation, un bandeau d'information sur les cookies s'affichera lors de votre première visite. Ces cookies étant strictement techniques et nécessaires, aucun consentement préalable n'est requis.</p>
        </section>

        <!-- Section 6 : Responsabilité -->
        <section class="section-cgu">
            <h3><span class="emoji">⚖️</span> Limitation de responsabilité</h3>
            <p>Loulou Play est fourni "tel quel" et nous mettons tout en œuvre pour vous offrir un service de qualité. Cependant :</p>
            <ul>
                <li>Ce site ne constitue pas un outil médical ou thérapeutique</li>
                <li>Nous ne garantissons pas de résultats spécifiques en termes d'apprentissage</li>
                <li>Les parents restent pleinement responsables de l'utilisation du site par leur enfant</li>
                <li>Nous ne pouvons être tenus responsables d'une utilisation inappropriée ou excessive</li>
                <li>En cas de doute sur le développement de votre enfant, consultez un professionnel de santé</li>
            </ul>
            
            <div class="highlight-box warning">
                <p><strong>Disclaimer médical :</strong> Ce site a une vocation éducative et ludique. Il ne remplace en aucun cas un diagnostic, un suivi ou un traitement médical par un professionnel qualifié.</p>
            </div>
        </section>

        <!-- Section 7 : Évolution du site -->
        <section class="section-cgu">
            <h3><span class="emoji">🚀</span> Évolution et amélioration continue</h3>
            <p>Loulou Play est un projet en constante évolution :</p>
            <ul>
                <li>De nouveaux jeux et activités sont régulièrement ajoutés</li>
                <li>Le site peut être temporairement indisponible pour maintenance</li>
                <li>Certaines fonctionnalités peuvent évoluer ou être modifiées</li>
                <li>Les présentes CGU peuvent être mises à jour pour refléter ces évolutions</li>
            </ul>
            <p>En cas de modification substantielle des CGU, nous vous en informerons via une notification visible sur le site.</p>
        </section>

        <!-- Section 8 : Contact -->
        <section class="section-cgu">
            <h3><span class="emoji">💬</span> Nous contacter</h3>
            <p>Vos retours, suggestions et questions sont les bienvenus ! Nous sommes à votre écoute pour améliorer continuellement Loulou Play.</p>
            <p><strong>Vous pouvez nous contacter pour :</strong></p>
            <ul>
                <li>Poser une question sur l'utilisation du site</li>
                <li>Signaler un problème technique</li>
                <li>Proposer une amélioration ou un nouveau jeu</li>
                <li>Partager votre expérience avec Loulou Play</li>
                <li>Demander une autorisation d'utilisation particulière</li>
            </ul>
            
            <a href="contact.php" class="btn-contact">📧 Nous contacter</a>
        </section>

        <!-- Section 9 : Mentions légales -->
        <section class="section-cgu">
            <h3><span class="emoji">📋</span> Mentions légales</h3>
            <p><strong>Éditeur du site :</strong><br>
            Loulou Play - Projet personnel<br>
            Responsable de publication : Christophe Siegenthaler<br>
            Romont, Canton de Fribourg, Suisse</p>
            
            <p><strong>Hébergement :</strong><br>
            Hostinger<br>
            HOSTINGER INTERNATIONAL LTD<br>
            61 Lordou Vironos Street, 6023 Larnaca, Chypre</p>
            
            <p><strong>Droit applicable :</strong><br>
            Les présentes CGU sont régies par le <strong>droit suisse</strong>. En cas de litige, nous privilégions toujours le dialogue et la résolution amiable. Le for juridique est à Romont, Canton de Fribourg, Suisse.</p>
        </section>

        <!-- Date de mise à jour -->
        <p class="date-maj">
            Dernière mise à jour : <?php echo date('d/m/Y'); ?><br>
            Version 1.0
        </p>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
