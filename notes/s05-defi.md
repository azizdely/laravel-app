# Session 05 - Défi : articles récents sur l'accueil

> Brouillon technique préparé avec l'aide d'un assistant IA. À vérifier et personnaliser avant remise.

- `latestPosts` contient au plus 3 articles : oui.
- Seuls les articles publiés sont transmis à la vue : oui.
- `home.blade.php` utilise `x-post-card` et `@forelse` : oui.
- Le test des articles récents passe : oui.
- Pourquoi `get()` et pas `paginate(3)` sur l'accueil ? La page d'accueil affiche un nombre fixe et limité d'articles sans contrôles de pagination. `take(3)->get()` renvoie directement cette petite collection, tandis que `paginate(3)` préparerait un paginateur et ses liens, inutiles sur cette page.
