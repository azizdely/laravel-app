# Session 13 - Défi

Sanctum est installé avec `php artisan install:api` : oui
La liste JSON contient seulement les articles publiés : oui
Le détail d'un article utilise une API Resource et refuse les brouillons : oui
La pagination accepte `per_page` de 1 à 50 et valide `page` : oui
Les ressources exposent les catégories, tags et l'auteur sans sérialiser le modèle brut : oui
Les tests API valident les réponses JSON, erreurs et limites de pagination : oui

Pourquoi limiter `per_page` à une valeur maximale ?

La limite évite qu'un client demande une collection trop importante dans une seule réponse. La pagination garde une taille de réponse prévisible et réduit le travail de la base de données.

Pourquoi utiliser une API Resource plutôt que retourner directement le modèle ?

La Resource choisit explicitement les champs et relations exposés. Le modèle peut évoluer sans modifier involontairement le contrat JSON ni exposer de nouveaux attributs.

Les captures d'écran Postman restent à prendre depuis un client API ; aucune capture n'a été simulée.
