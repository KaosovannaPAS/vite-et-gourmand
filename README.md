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

L'application est entièrement Dockerisée pour une mise en route simple et rapide, sans prérequis majeurs hormis Docker.

1. **Prérequis** : Avoir [Docker](https://www.docker.com/) et Docker Compose installés sur votre machine (Windows, Mac ou Linux).
2. **Cloner le projet** (si pertinent) ou vous placer dans le dossier racine :
   ```bash
   cd Vite-et-gourmand
   ```
3. **Lancement via Docker Compose** :
   Dans le terminal, à la racine du projet, tapez simplement :
   ```bash
   docker-compose up -d --build
   ```
   *Ce processus va compiler l'image PHP/Apache, télécharger l'image de MongoDB et de MySQL, configurer les extensions (PDO, MongoDB driver) et lancer les conteneurs.*

4. **Installation de la Base de Données** :
   Dans un premier temps, importez le fichier `database.sql` dans votre instance de base de données (si ce n'est pas fait automatiquement). Vous pouvez accéder à la db locale via PhpMyAdmin ou tout outil GBD avec les infos :
   - Host : `127.0.0.1` (ou `localhost`)
   - Port : `3306`
   - User : `root`
   - Password : `root`

5. **Accéder à l'application** :
   L'application sera disponible sur votre navigateur via : **http://localhost:8080**

*(Alternative XAMPP/WAMP) : Si vous n'utilisez pas Docker, placez le dossier à la racine de votre serveur local, configurez `noyau_backend/configuration/config.php` et importez `database.sql`.*

## Comptes de Démonstration

- **Administrateur** : `admin@vite-et-gourmand.fr` / `admin123`
- **Utilisateur** : `client@vite-et-gourmand.fr` / `client123` (si existant, ou créez un compte via l'inscription).
