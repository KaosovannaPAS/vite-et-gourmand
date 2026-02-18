<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand - Traiteur d'exception</title>
    <!-- Polices Google -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- CSS Principal -->
    <link rel="stylesheet" href="/Vite-et-gourmand/interface_frontend/ressources/css/style.css">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Static Background Controlled via CSS -->

    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="logo">
                <a href="/Vite-et-gourmand/" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--secondary-color); text-decoration: none; display: flex; align-items: center; gap: 0.8rem; letter-spacing: 2px;">
                    <div style="border: 2px solid var(--secondary-color); padding: 5px 10px; border-radius: 50% 50% 0 0; border-bottom: none;">
                        <i class="fas fa-utensils" style="color: var(--secondary-color);"></i>
                    </div>
                    <span style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Vite & Gourmand</span>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/Vite-et-gourmand/#history">Notre Histoire</a></li>
                    <li><a href="/Vite-et-gourmand/interface_frontend/pages/menus.php">Nos Menus</a></li>
                    <li><a href="/Vite-et-gourmand/#news">Nos Actualités</a></li>
                    <li><a href="/Vite-et-gourmand/interface_frontend/pages/contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <li><a href="/Vite-et-gourmand/interface_frontend/admin/dashboard.php">Espace Admin</a></li>
                        <?php
    elseif ($_SESSION['user']['role'] === 'employe'): ?>
                            <li><a href="/Vite-et-gourmand/interface_frontend/employe/dashboard.php">Espace Employé</a></li>
                        <?php
    else: ?>
                            <li><a href="/Vite-et-gourmand/interface_frontend/pages/dashboard.php">Mon Compte</a></li>
                        <?php
    endif; ?>
                        <li><a href="/Vite-et-gourmand/interface_frontend/pages/logout.php" class="btn btn-primary">Déconnexion</a></li>
                    <?php
else: ?>
                        <li><a href="/Vite-et-gourmand/interface_frontend/pages/login.php" class="btn btn-primary">Connexion</a></li>
                    <?php
endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
