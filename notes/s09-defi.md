# Session 09 - Défi

Le bandeau s'affiche sur toutes les pages quand `SITE_READ_ONLY=true` : oui
Le bandeau disparaît quand `SITE_READ_ONLY=false` : oui
La page Article introuvable propose au plus trois articles publiés, sans brouillon : oui
Aucune `LazyLoadingViolationException` sur `/posts/999` : oui
Les nouveaux tests passent et `php artisan test` est vert : oui

Pourquoi le bandeau est-il dans le layout et pas dans chaque vue ?

Le layout entoure toutes les pages, y compris les pages d'erreur. Le placer là permet de présenter le même état partout sans dupliquer le composant dans chaque vue.

Pourquoi le test du bandeau n'a-t-il pas besoin de modifier `.env` ?

Le test change `config('site.read_only')` pour cette exécution seulement. La valeur de `.env` reste indépendante et les autres tests continuent à utiliser le mode normal.
