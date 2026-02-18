# Vite & Gourmand - Application Traiteur

## Architecture du Projet
Ce projet utilise une architecture **Frontend / Backend** explicite pour une meilleure séparation des responsabilités.

### 📂 Structure des dossiers

```
/
├── backend/                  # LOGIQUE METIER & DONNEES
│   ├── api/                 # Endpoints JSON (ex: stats.php, menus.php)
│   └── config/              # Connexions BDD (db.php, mongo.php)
│
├── frontend/                 # PRESENTATION & VUES
│   ├── assets/              # Ressources statiques (CSS, JS, Images)
│   ├── includes/            # Fragments HTML (Header, Footer)
│   ├── pages/               # Pages publiques (Menus, Contact, Login...)
│   ├── admin/               # Interface Administrateur
│   └── employe/             # Interface Employé
1.- **noyau_backend/** : Logique métier et accès aux données.
  - `api/` : Endpoints API.
  - `configuration/` : Fichiers de configuration (DB, Mongo).
- **interface_frontend/** : Vues et assets.
  - `ressources/` : CSS, JS, Images.
  - `composants/` : Éléments réutilisables (header, footer).
  - `pages/` : Pages publiques.
  - `admin/` : Pages d'administration.
  - `employe/` : Pages employés.
- **index.php** : Point d'entrée.

## Installation

1. **Base de Données**
   - Importez `database.sql` dans MySQL (Base : `vite_et_gourmand`).
   - Configurez `backend/config/db.php` si nécessaire.

2. **Lancement**
   - Placez le dossier `Vite-et-gourmand` dans votre serveur web (ex: `htdocs` de XAMPP).
   - Accédez à `http://localhost/Vite-et-gourmand`.

## Comptes de Démonstration

- **Admin** : `admin@vite-et-gourmand.fr` / `admin123`
- **Utilisateur** : Créez un compte via l'inscription.
