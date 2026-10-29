# Session 08 - Défi

La route `posts.tag` utilise `{tag:slug}` : oui  
La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui  
Les tags des cartes sont des liens : oui  
L'accueil n'affiche que les tags qui ont au moins un article publié : oui  
Les trois nouveaux tests passent et `php artisan test` est vert : oui

## Pourquoi filtrer les tags vides après `get()` plutôt que d'afficher (0) ?

Un tag sans article publié n'est pas utile dans cette liste. Le filtrer permet de garder l'accueil pertinent et d'éviter de présenter des tags comme populaires alors qu'ils n'ont aucun contenu public.

## Combien de requêtes ajoute la liste des tags populaires, et pourquoi pas une par tag ?

Elle ajoute une seule requête à la page d'accueil : `withCount()` calcule le nombre d'articles publiés dans cette requête. Le comptage n'est donc pas relancé séparément pour chaque tag, ce qui évite un problème N+1.
