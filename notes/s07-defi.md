# Session 07 - Défi : articles de la même catégorie

> Brouillon technique préparé avec l'aide d'un assistant IA. À vérifier et personnaliser avant remise.

- La section « Dans la même catégorie » apparaît sous un article publié qui a une catégorie : oui.
- L'article affiché n'apparaît pas dans sa propre section : oui.
- Un brouillon de la même catégorie n'apparaît pas : oui.
- La page s'ouvre sans `LazyLoadingViolationException` : oui.
- Le nouveau test ciblé et la suite complète passent : oui (38 tests).
- Combien de requêtes SQL chargent la section, et pourquoi pas une par carte ? Quatre : une pour les articles associés, puis une pour chaque relation (`user`, `category` et `tags`). `with()` regroupe les chargements, donc le nombre de requêtes ne dépend pas du nombre de cartes.
