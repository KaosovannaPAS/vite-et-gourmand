<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../../../configuration/db.php';

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
$prenom = htmlspecialchars($input['prenom'] ?? '');
$email = htmlspecialchars($input['email'] ?? '');
$password = $input['password'] ?? '';
$confirm_password = $input['confirm_password'] ?? '';
$telephone = htmlspecialchars($input['telephone'] ?? '');
$adresse = htmlspecialchars($input['adresse'] ?? '');

if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirm_password) || empty($telephone) || empty($adresse)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

if (strlen($password) < 10 ||
!preg_match('/[A-Z]/', $password) ||
!preg_match('/[a-z]/', $password) ||
!preg_match('/[0-9]/', $password) ||
!preg_match('/[^a-zA-Z0-9]/', $password)) {
    echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.']);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, telephone, adresse, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");

    if ($stmt->execute([$nom, $prenom, $email, $hash, $telephone, $adresse])) {
        echo json_encode(['success' => true, 'message' => 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.']);
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de l\'inscription.']);
    }
}
catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur base de données.']);
}
