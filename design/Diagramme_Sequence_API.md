# Diagramme de Séquence : Architecture Fetch Front-End vers API Back-End

Ce diagramme illustre le flux typique de données entre les pages statiques (`.html`), le JavaScript local (`Fetch API`), et le backend PHP (Contrôleurs MVC connectés aux bases MySQL et MongoDB).

```mermaid
sequenceDiagram
    participant U as Utilisateur (Navigateur)
    participant JS as JavaScript (Front-End)
    participant API as API PHP (Back-End)
    participant SQL as Base de données MySQL
    participant NoSQL as Base de données MongoDB
    
    U->>JS: Charge page statique (ex: menus.html)
    Note over JS: Événement DOMContentLoaded
    JS->>API: Requête GET /api/v1/menus.php (Fetch)
    activate API
    
    API->>API: Vérification Session/Auth (middleware)
    API->>SQL: SELECT * FROM menus (via PDO préparé)
    activate SQL
    SQL-->>API: Renvoie les données tabulaires
    deactivate SQL
    
    API->>NoSQL: logAction("Consultation Catalogue")
    activate NoSQL
    NoSQL-->>API: OK (Log enregistré)
    deactivate NoSQL
    
    API-->>JS: Réponse JSON (données des menus)
    deactivate API
    
    JS->>JS: Traitement du JSON (Mappage)
    JS->>U: Manipulation du DOM (Création dynamique des cartes de repas)
    
    Note over U,JS: Interaction Utilisateur
    U->>JS: Clique sur "Ajouter au panier"
    JS->>API: Requête POST /api/v1/order.php (Corps JSON)
    activate API
    API->>SQL: INSERT INTO orders ... (via Objet Commande)
    activate SQL
    SQL-->>API: Command ID généré
    deactivate SQL
    API-->>JS: Réponse JSON (success, order_id)
    deactivate API
    JS->>U: Met à jour l'icône du panier / Redirige vers order.html
```

### Explications Architecturales
1. **Séparation des préoccupations (SoC)** : Le front-end ne connaît ni PHP ni SQL. Il se contente d'afficher le DOM et d'orchestrer les appels asynchrones `fetch`.
2. **Stateless vs Stateful** : Bien que l'API utilise des Sessions PHP (`$_SESSION`), toutes les transmissions de données textuelles et de formulaires se font en JSON pour être agnostiques par rapport à la technologie front.
3. **Usage Mixte BDD** : 
   - **MySQL** : Garantit l'intégrité relationnelle (Utilisateurs -> Commandes -> Menus).
   - **MongoDB** : Utilisé pour du logging d'action rapide et asynchrone sans bloquer la table relationnelle.
