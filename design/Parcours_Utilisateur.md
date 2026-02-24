# Parcours Utilisateur (User Journey) - Vite & Gourmand

Ce document décrit les parcours interactifs des différentes typologies d'utilisateurs. Il remplace le livrable PDF demandé et a vocation à être exporté tel quel.

## 1. Parcours Client (Vue Client)

### Scénario Nominal : De la découverte à la commande

1. **Atterrissage (Landing) : `index.html`**
   - Le client arrive sur la page d'accueil.
   - Il y découvre le Hero Banner avec le Carrousel "Vite & Gourmand".
   - Un appel à l'action (CTA) le redirige vers le catalogue des menus.

2. **Parcours du Catalogue : `menus.html`**
   - Un script JS dynamique interroge `api/v1/menus.php` pour charger la carte.
   - Les cartes produits s'affichent par catégorie (Vegan, Sans Gluten, Classique).
   - Le client clique sur "Voir le détail" de la *Gourmandise Bordelaise*.

3. **Choix et Ajout au panier : `menu-detail.html?id=2`**
   - Affichage des spécificités du plat.
   - Le client gère la quantité avec le stepper JS (+ / -) avec un minimum requis affiché dynamiquement à l'écran.
   - Il clique sur "Passer Commande".

4. **Tunnel de Commande (Sécurisé) : `order.html`**
   - L'API `auth/me.php` est interrogée.
   - Si le client **n'est pas** connecté -> Redirection vers `login.html` puis retour automatique.
   - S'il est connecté, le formulaire pré-remplit les données (nom, prénoms récupérés depuis SQL).
   - Paiement factice/Soumission asynchrone vers `api/v1/order.php`.

5. **Tableau de Bord / Suivi : `dashboard.html`**
   - Retour visuel avec succès. Le client consulte son historique sur le tableau de bord.
   - Une fois la commande passée au statut "terminée", un bouton "Laisser un avis" apparaît (redirigeant vers `review.html`).

---

## 2. Parcours Administrateur (Vue Admin)

### Scénario Nominal : Gestion des opérations vitales

1. **Authentification Renforcée : `login.html`**
   - L'administrateur entre ses identifiants. 
   - L'API `/api/v1/auth/login.php` vérifie le rôle (champ `role = 'admin'` en BDD).
   - Le JavaScript Front (`login.js`) analyse la réponse JSON et route instantanément vers `/admin/dashboard.html`.

2. **Tableau de Bord KPI : `admin/dashboard.html`**
   - Un appel unifié `api/v1/stats.php` peuple l'écran des métriques (chiffre d'affaires jour, tickets en attente).
   - Le dashboard est totalement verrouillé : si l'admin essaie d'y accéder sans session `$_SESSION`, le JS le redirige à l'accueil.

3. **Traitement d'une commande : `admin/commandes.html`**
   - Liste dynamique des commandes via Datatable JS native.
   - L'admin sélectionne une liste déroulante `<select>` pour passer le statut de la commande #12 de `"En attente"` à `"En préparation"`.
   - L'événement `onChange` déclenche un Fetch `PUT /api/v1/orders.php?id=12`. La mise à jour est en temps réel sans rechargement.
   
4. **Gestion du personnel : `admin/employes.html`**
   - L'admin clique sur le bouton "Ajouter". Une Modal HTML native s'ouvre.
   - Il crée le profil d'un livreur. Le JSON est soumis à `api/v1/users.php`.
   - L'enregistrement se fait dans MySQL et un log MongoDB est généré : `{"action": "creation_employe", "target": "livreur"}`.
