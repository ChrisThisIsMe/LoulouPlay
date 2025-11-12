<?php
session_start();
header('Content-Type: application/json');

// Générer un nombre aléatoire entre 1 et 10
$nombre_cible = rand(1, 10);

// Stocker ce nombre en session (comme demandé, bien que la validation se fera côté client)
$_SESSION['target'] = $nombre_cible;

// Préparer la réponse à envoyer au frontend
$response = [
    'success' => true,
    'target' => $nombre_cible
];

// Envoyer la réponse au format JSON
echo json_encode($response);
?>
