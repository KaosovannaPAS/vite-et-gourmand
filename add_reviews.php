<?php
require_once __DIR__ . '/noyau_backend/configuration/db.php';

echo "Adding 10 new reviews with users and orders...\n";
$pdo->exec("SET NAMES 'utf8mb4'");

$new_reviews = [
    [
        'nom' => 'Lecomte', 'prenom' => 'Aurélie',
        'avatar' => 'https://ui-avatars.com/api/?name=Aurélie+Lecomte&background=e74c3c&color=fff',
        'menu_id' => 1, 'note' => 5,
        'comment' => 'Le Menu Noël Féerique était splendide. Une vraie magie dans l\'assiette !'
    ],
    [
        'nom' => 'Bernard', 'prenom' => 'Julien',
        'avatar' => 'https://ui-avatars.com/api/?name=Julien+Bernard&background=3498db&color=fff',
        'menu_id' => 5, 'note' => 5,
        'comment' => 'Incroyable qu\'un menu 100% Végétal puisse être aussi gourmand. Bravo chef !'
    ],
    [
        'nom' => 'Dubois', 'prenom' => 'Chloé',
        'avatar' => 'https://ui-avatars.com/api/?name=Chloé+Dubois&background=9b59b6&color=fff',
        'menu_id' => 3, 'note' => 5,
        'comment' => 'Une Saint-Valentin inoubliable grâce à vous. Service discret et efficace.'
    ],
    [
        'nom' => 'Moreau', 'prenom' => 'Lucas',
        'avatar' => 'https://ui-avatars.com/api/?name=Lucas+Moreau&background=f1c40f&color=fff',
        'menu_id' => 4, 'note' => 4,
        'comment' => 'Le voyage en Asie était dépaysant. Les saveurs étaient justes et équilibrées.'
    ],
    [
        'nom' => 'Petit', 'prenom' => 'Camille',
        'avatar' => 'https://ui-avatars.com/api/?name=Camille+Petit&background=2ecc71&color=fff',
        'menu_id' => 7, 'note' => 5,
        'comment' => 'Étant Bordelaise, je suis exigeante, mais là... chapeau bas pour les spécialités !'
    ],
    [
        'nom' => 'Roux', 'prenom' => 'Gabriel',
        'avatar' => 'https://ui-avatars.com/api/?name=Gabriel+Roux&background=e67e22&color=fff',
        'menu_id' => 6, 'note' => 5,
        'comment' => 'Les fruits de mer étaient d\'une fraîcheur absolue. Je recommanderai.'
    ],
    [
        'nom' => 'Fournier', 'prenom' => 'Léa',
        'avatar' => 'https://ui-avatars.com/api/?name=Léa+Fournier&background=1abc9c&color=fff',
        'menu_id' => 2, 'note' => 5,
        'comment' => 'Le Menu Nouvel An Prestige a bluffé tous nos invités. Merci pour tout.'
    ],
    [
        'nom' => 'Girard', 'prenom' => 'Hugo',
        'avatar' => 'https://ui-avatars.com/api/?name=Hugo+Girard&background=34495e&color=fff',
        'menu_id' => 1, 'note' => 4,
        'comment' => 'Très bonne prestation pour notre repas de fin d\'année d\'entreprise.'
    ],
    [
        'nom' => 'Andre', 'prenom' => 'Manon',
        'avatar' => 'https://ui-avatars.com/api/?name=Manon+Andre&background=d35400&color=fff',
        'menu_id' => 5, 'note' => 5,
        'comment' => 'Même les non-végétariens ont adoré le menu végétal. Une réussite.'
    ],
    [
        'nom' => 'Mercier', 'prenom' => 'Louis',
        'avatar' => 'https://ui-avatars.com/api/?name=Louis+Mercier&background=7f8c8d&color=fff',
        'menu_id' => 7, 'note' => 5,
        'comment' => 'Le canelé en dessert était parfait, croustillant et moelleux. Un délice.'
    ]
];

foreach ($new_reviews as $rev) {
    // 1. Créer User
    // Email unique requis, on génère un faux basé sur le nom
    $email = strtolower($rev['prenom'] . '.' . $rev['nom'] . rand(100, 999) . '@example.com');
    // Check if email exists to avoid dupes if run multiple times
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "Skipping existing user $email\n";
        continue;
    }

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password_hash, role_id, avatar_url) VALUES (?, ?, ?, ?, 2, ?)");
    $stmt->execute([$rev['nom'], $rev['prenom'], $email, password_hash('password', PASSWORD_DEFAULT), $rev['avatar']]);
    $user_id = $pdo->lastInsertId();

    // 2. Créer Order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, date_livraison, statut, prix_total) VALUES (?, CURRENT_DATE, 'terminee', 100.00)");
    $stmt->execute([$user_id]);
    $order_id = $pdo->lastInsertId();

    // 3. Créer Order Item (Lien Menu)
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantite, prix_unitaire) VALUES (?, ?, 2, 50.00)");
    $stmt->execute([$order_id, $rev['menu_id']]);

    // 4. Créer Review
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, order_id, note, commentaire, valide) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$user_id, $order_id, $rev['note'], $rev['comment']]);

    echo "Added review for {$rev['prenom']} {$rev['nom']}\n";
}

echo "Done adding reviews.";
?>
