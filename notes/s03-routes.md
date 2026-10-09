# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par `route('about')` : `http://localhost:8000/a-propos`

URL produite par `route('posts.show', 'routes-et-controleurs')` : `http://localhost:8000/posts/routes-et-controleurs`

URL produite par `route('posts.archive', 2025)` : `http://localhost:8000/posts/archive/2025`

Pourquoi `/posts/archive/abc` et `/posts/article-inconnu` répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?

`/posts/archive/abc` ne satisfait pas la contrainte `whereNumber('year')` de la route d'archive. `/posts/article-inconnu` satisfait la contrainte de slug, mais `PostController::show` appelle `abort_unless(array_key_exists($slug, $posts), 404)` quand l'article n'existe pas.

Que se passe-t-il si une vue appelle `route('posts.show')` sans paramètre ?

Laravel lève une `Illuminate\Routing\Exceptions\UrlGenerationException`, car la route nommée `posts.show` exige la valeur du paramètre `{slug}`.
