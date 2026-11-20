# Session 11 - Défi

/mes-articles ne montre que mes articles, brouillons compris : oui
Un visiteur qui ouvre /mes-articles arrive sur /connexion : oui
Le cookie remember_web_ apparaît quand la case est cochée : la connexion persistante est activée via Auth::attempt(..., true)
Les deux nouveaux tests passent et php artisan test est vert : oui

Pourquoi partir de $request->user()->posts() plutôt que de Post::where('user_id', $request->user()->id) ?
La relation limite la requête aux articles de l'utilisateur authentifié et évite de s'appuyer sur un identifiant fourni par le client.

Pourquoi la case remember ne doit-elle pas être dans le tableau passé à Auth::attempt() ?
Auth::attempt traite les clés fournies comme des colonnes à rechercher. remember n'est pas une colonne users; le booléen est donc passé comme second argument.
