# Session 10 - Robustesse

TagAdminTest contient 7 tests verts : oui
/posts/categorie/inconnue affiche Catégorie introuvable avec le statut 404 : oui
php artisan test est vert : oui

1. Pourquoi le message de messages() l'emporte-t-il sur celui de lang/fr/validation.php ?
   Laravel privilégie le message le plus spécifique au champ et à la règle, défini par la Form Request. Le fichier de langue ne fournit que le message générique quand aucune surcharge précise n'existe.

2. Pourquoi phpunit.xml fixe-t-il APP_LOCALE ?
   Les tests comparent les textes français. Fixer la locale dans phpunit.xml garantit le même résultat sur chaque poste, quel que soit le contenu de .env.

3. Pourquoi CategoryController n'a-t-il besoin d'aucun try/catch ?
   La validation, la liaison de modèle, le middleware et le gestionnaire d'exceptions traitent déjà leurs erreurs en réponses adaptées. Un try/catch dans le contrôleur masquerait ces responsabilités au lieu de les améliorer.
