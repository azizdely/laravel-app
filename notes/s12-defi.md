# Session 12 - Défi

Les routes `/admin/categories` et `/admin/tags` sont réservées aux administrateurs : oui
Les auteurs peuvent voir les articles publiés et leurs propres brouillons : oui
Les administrateurs peuvent voir tous les articles : oui
Un visiteur ne peut pas voir un brouillon : oui
Les tests Pest du contrôle d'accès et de visibilité passent : oui

Pourquoi grouper les conditions du scope `visibleTo` ?

Le groupe garantit que la condition publique (article publié) ou la condition de propriété reste combinée aux filtres ajoutés par l'appelant. Sans ce groupement, un `orWhere` pourrait exposer des brouillons hors des autres contraintes de la requête.

Pourquoi garder à la fois le middleware `admin` et le Gate `manage-tags` ?

Le middleware protège tout le groupe de routes du back-office. Le Gate exprime la capacité métier utilisée dans les contrôleurs, les formulaires et la navigation. Les deux vérifications empêchent qu'un nouveau point d'entrée de gestion des tags oublie la règle.
