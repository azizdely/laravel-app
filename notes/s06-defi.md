# Session 06 - Défi : publier et dépublier

> Brouillon technique préparé avec l'aide d'un assistant IA. À vérifier et personnaliser avant remise.

- La route `posts.publication` apparaît dans `route:list` avec le verbe PATCH : oui.
- Le bouton affiche « Publier » sur un brouillon et « Dépublier » sur un article publié : oui.
- `published_at` est rempli à la publication et vidé à la dépublication : oui.
- Le test de publication et la suite complète passent : oui.
- Pourquoi PATCH et pas PUT ni GET pour ce bouton ? L'action modifie seulement l'état de publication, donc PATCH décrit mieux la mise à jour partielle. GET doit rester sans effet de bord, et PUT sert plutôt à remplacer l'ensemble de la ressource.
