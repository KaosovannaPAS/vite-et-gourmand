# Vite & Gourmand - Application Traiteur

Ce projet répond au cahier des charges final pour le titre professionnel, implémentant une architecture moderne, sécurisée, avec une gestion complète des menus, commandes et employés.

## Architecture du Projet

Le projet a été refondu pour adopter une solide séparation **Frontend / Backend** :

- **Approche MVC / API-Centric** : Le backend (`noyau_backend/`) gère exclusivement la logique métier et expose des API REST (`/api/v1/`). Il n'y a plus de rendu de vues côté serveur.
- **Séparation Front/Back** : Le frontend (`interface_frontend/`) est composé de fichiers HTML/CSS purs et s'appuie sur JavaScript (`fetch`) pour communiquer avec le backend.
- **Programmation Orientée Objet (POO)** : Le code PHP a été refondu en classes MVC (ex: `models/Menu.php`, `models/Order.php`, `models/User.php`).

### 📂 Structure des dossiers

```
/
├── noyau_backend/            # LOGIQUE METIER & DONNEES (BACKEND)
│   ├── api/v1/               # Endpoints REST JSON (menus.php, orders.php, stats.php, users.php)
│   ├── models/               # Classes POO (User, Menu, Order, AdminLog)
│   └── configuration/        # Connexions BDD (db.php, mongo.php)
│
├── interface_frontend/       # PRESENTATION & VUES (FRONTEND)
│   ├── ressources/           # CSS, JS (AJAX/Fetch), Images
│   ├── composants/           # Fragments HTML (Header, Footer)
│   ├── admin/                # Interface Administrateur (Gérée via API)
│   ├── employe/              # Interface Employé
│   └── pages/                # Pages publiques (Menus interactifs, Dashboard Client...)
│
├── design/                   # Maquettes et Diagrammes techniques (Livrables)
└── Dockerfile & docker-compose.yml
```

## Fonctionnalités Principales

- **Catalogue Interactif** : Navigation fluide avec filtres et simulateurs de devis dynamiques.
- **Espace Client** : Historique des commandes et gestion de compte.
- **Administration** : Dashboard statistique dynamique (MongoDB + MySQL), gestion complète des employés (CRUD) et suivi des commandes.
- **API Sécurisée** : Requêtes PDO préparées pour prévenir les injections SQL, gestion des accès par rôle.
- **Conformité** : Mentions légales RGPD, accessibilité RGAA de base prise en compte.

## Déploiement Local (Docker)

L'application est entièrement Dockerisée avec une architecture allégée (un seul conteneur `php:8.2-apache`) qui se connecte directement à la base de données distante de production (TiDB).

1. **Prérequis** : Avoir [Docker](https://www.docker.com/) et Docker Desktop en cours d'exécution sur votre machine.
2. **Variables d'environnement** : Vous devez avoir le fichier `.env.production` à la racine de votre projet. Celui-ci contient les accès sécurisés à la base TiDB exploités par Vercel.
3. **Lancement via Docker Compose** :
   Dans le terminal, à la racine du projet, tapez simplement :
   ```bash
   docker-compose up -d --build
   ```
   *Ce processus va compiler l'image PHP/Apache, configurer les extensions (PDO MySQL) et monter automatiquement le code de votre machine dans le conteneur.*

4. **Développement Fluide** :
   Le projet utilise un volume partagé. Toute modification effectuée sur vos fichiers HTML, CSS, JS ou PHP sur votre machine sera immédiatement répercutée dans le conteneur sans avoir besoin de le reconstruire.

5. **Accéder à l'application** :
   L'application sera disponible sur votre navigateur via : **http://localhost:8080**

## Comptes de Démonstration

- **Administrateur** : `admin@vite-et-gourmand.fr` / `admin123`
- **Utilisateur** : `client@vite-et-gourmand.fr` / `client123` (si existant, ou créez un compte via l'inscription).
