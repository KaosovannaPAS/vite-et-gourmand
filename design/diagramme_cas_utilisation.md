# Diagramme de Cas d'Utilisation : Vite-et-Gourmand

```mermaid
usecaseDiagram
    actor Client
    actor Employé
    actor Administrateur

    package "Système Vite-et-Gourmand" {
        usecase "S'inscrire / Se connecter" as UC1
        usecase "Consulter le catalogue" as UC2
        usecase "Passer une commande" as UC3
        usecase "Laisser un avis" as UC4
        
        usecase "Préparer une commande" as UC5
        usecase "Mettre à jour le statut (Livré)" as UC6
        
        usecase "Gérer les menus (CRUD)" as UC7
        usecase "Gérer les employés (CRUD)" as UC8
        usecase "Gérer les commandes" as UC9
        usecase "Consulter les logs / stats" as UC10
    }

    Client --> UC1
    Client --> UC2
    Client --> UC3
    Client --> UC4

    Employé --> UC1
    Employé --> UC5
    Employé --> UC6
    Employé --> UC2

    Administrateur --> UC1
    Administrateur --> UC7
    Administrateur --> UC8
    Administrateur --> UC9
    Administrateur --> UC10
    Administrateur --> UC5
    Administrateur --> UC6
```
