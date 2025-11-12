<?php
/**
 * Page Qui sommes-nous - Loulou Play
 */

// Chargement de la configuration
require_once 'config.php';

// Titre de la page
$page_title = 'Qui sommes-nous';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez l'histoire de Loulou Play, un site éducatif gratuit créé par un papa pour aider sa fille et tous les enfants avec un retard de langage.">
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
        
        .site-header .tagline {
            font-size: 1.5em;
            color: #5E35B1;
            font-weight: 600;
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
            margin-bottom: 30px;
            text-align: center;
        }
        
        .conteneur-principal h3 {
            color: #667eea;
            font-size: 1.8em;
            margin-top: 35px;
            margin-bottom: 15px;
        }
        
        .conteneur-principal p {
            color: #2C3E50;
            font-size: 1.2em;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #F3E5F5 0%, #E1BEE7 100%);
            padding: 25px;
            border-radius: 20px;
            margin: 30px 0;
            border-left: 5px solid #7C4DFF;
        }
        
        .signature {
            text-align: center;
            margin-top: 50px;
            font-size: 1.3em;
            color: #764ba2;
            font-weight: 600;
        }
        
        @keyframes flotte {
            0%, 100% { transform: translateY(0px) rotate(-8deg); }
            50% { transform: translateY(-20px) rotate(8deg); }
        }
        
        @media (max-width: 750px) {
            .site-header h1 {
                font-size: 2.5em;
            }
            
            .conteneur-principal {
                padding: 30px 20px;
            }
            
            .conteneur-principal h2 {
                font-size: 1.8em;
            }
            
            .conteneur-principal h3 {
                font-size: 1.5em;
            }
            
            .conteneur-principal p {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>

    <!-- Mascotte flottante -->
    <img src="<?php echo PATH_IMG; ?>mascotte-hibou.png" alt="Hibou Loulou" class="mascotte-flottante">

    <!-- Header simplifié -->
    <header class="site-header">
        <!-- Navigation -->
        <?php include 'includes/nav.php'; ?>
        <h1>loulou play</h1>
        <p class="tagline"><?php echo SITE_TAGLINE; ?></p>
    </header>

    <!-- Contenu principal -->
    <main class="conteneur-principal">
        <h2>🦉 Qui sommes-nous ?</h2>
        
        <p>Bonjour à tous,</p>
        
        <p>Je suis le papa d'une adorable petite fille de 3 ans qui présente un retard de langage. Comme beaucoup de parents dans cette situation, j'ai cherché des ressources pour l'accompagner dans son apprentissage. Malheureusement, j'ai rapidement constaté qu'il n'existe quasiment <strong>rien de gratuit</strong> et de qualité sur internet. Les rares outils disponibles sont souvent bardés de publicités envahissantes qui apparaissent toutes les deux minutes, rendant l'expérience frustrante et contre-productive pour l'enfant.</p>
        
        <h3>💡 Une solution créée avec amour</h3>
        
        <p>En tant que développeur, j'ai décidé de prendre les choses en main et de créer mon propre site éducatif pour aider ma fille à faire l'acquisition du langage. Ce projet n'est pas sorti de nulle part : j'ai eu la chance de pouvoir consulter des <strong>experts du domaine</strong> (orthophonistes, éducateurs spécialisés) qui ont supervisé l'approche pédagogique et validé les contenus.</p>
        
        <div class="highlight-box">
            <p><strong>🎯 L'objectif de Loulou Play :</strong> Offrir un environnement d'apprentissage <strong>gratuit, sans publicité, et conçu spécifiquement</strong> pour les enfants ayant des difficultés de langage.</p>
        </div>
        
        <h3>❤️ Un site pour tous les parents</h3>
        
        <p>Aujourd'hui, je dédie ce site à <strong>toutes les familles</strong> qui se retrouvent dans cette situation et qui ont besoin d'un support de qualité. Si vous êtes parent d'un enfant avec un retard de langage, sachez que vous n'êtes pas seul et que des outils existent pour vous accompagner.</p>
        
        <h3>👨‍👧 L'importance de l'accompagnement</h3>
        
        <p><strong>Attention :</strong> il ne faut surtout pas laisser votre enfant livré à lui-même devant un écran. Ces outils sont conçus pour être utilisés <strong>avec vous</strong>, en votre présence. L'apprentissage du langage est un <strong>moment de partage privilégié</strong> entre vous et votre enfant. Profitez-en pour créer des souvenirs, rire ensemble, et célébrer chaque petit progrès.</p>
        
        <div class="highlight-box">
            <p><strong>💬 Votre avis compte !</strong> Ce site est en constante évolution. Tous vos commentaires, suggestions et retours d'expérience sont les bienvenus. N'hésitez pas à me contacter pour me faire part de vos idées ou simplement pour partager votre parcours.</p>
        </div>
        
        <div class="signature">
            <p>Avec toute ma gratitude,</p>
            <p>Le créateur de Loulou Play 🦉</p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
