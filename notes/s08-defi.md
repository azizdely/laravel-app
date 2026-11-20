# Session 08 - Défi

La route `posts.tag` utilise `{tag:slug}` : oui
La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui
Les tags des cartes sont des liens : oui
L'accueil n'affiche que les tags qui ont au moins un article publié : oui
Les trois nouveaux tests passent et `php artisan test` est vert : oui

Pourquoi filtrer les tags vides après `get()` plutôt que d'afficher (0) ?

Les tags sans article publié n'aident pas à parcourir le contenu. Les masquer évite de proposer une page qui ne contient aucun article.

Combien de requêtes ajoute la liste des tags populaires, et pourquoi pas une par tag ?

La liste ajoute une seule requête pour charger les tags et leur compteur calculé par `withCount`. Le nombre d'articles est calculé dans la requête SQL, plutôt que de charger chaque relation séparément.
