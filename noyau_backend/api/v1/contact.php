<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

$nom = htmlspecialchars($input['nom'] ?? '');
$email = htmlspecialchars($input['email'] ?? '');
$objet = htmlspecialchars($input['objet'] ?? '');
$message = htmlspecialchars($input['message'] ?? '');

if (empty($nom) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir les champs obligatoires.']);
    exit;
}

$devisInfo = "";
if ($objet === 'devis') {
    $date = htmlspecialchars($input['date_event'] ?? '');
    $convives = htmlspecialchars($input['convives'] ?? '');
    $type = htmlspecialchars($input['type_event'] ?? '');
    $budget = htmlspecialchars($input['budget'] ?? '');
    $devisInfo = "\n\n--- Détails Devis ---\nDate: $date\nConvives: $convives\nType: $type\nBudget: $budget €";
}

// Simulation d'envoi de mail
// mail('contact@vite-et-gourmand.fr', "Contact: $objet", $message . $devisInfo, "From: $email");

echo json_encode([
    'success' => true,
    'message' => "Votre demande ($objet) a bien été envoyée ! Une réponse vous sera apportée sous 24h."
]);
