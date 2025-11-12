<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Loulou Play</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Comic Sans MS', 'Arial Rounded MT Bold', sans-serif;
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            min-height: 100vh;
            padding: 0;
        }

        /* Container principal avec largeur standardisée */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        nav {
            background: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 25px;
            margin: 20px 0;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }

        nav ul li a {
            text-decoration: none;
            color: #6c5ce7;
            font-size: 1.2em;
            font-weight: bold;
            transition: all 0.3s ease;
            padding: 8px 15px;
            border-radius: 15px;
        }

        nav ul li a:hover {
            background: #6c5ce7;
            color: white;
            transform: scale(1.05);
        }

        /* Container de contenu */
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <nav>
            <ul>
                <li><a href="index.php">🏠 Accueil</a></li>
                <li><a href="qui-sommes-nous.php">ℹ️ Qui sommes-nous</a></li>
                <li><a href="contact.php">📧 Contact</a></li>
            </ul>
        </nav>
    </div>
