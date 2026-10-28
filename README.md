# Mini-CMS

Mini-CMS est un projet d’atelier Laravel consacré au routage, aux contrôleurs et aux layouts Blade.

## État actuel

- Routes organisées et nommées avec des groupes `prefix` et `name`.
- Contrôleurs `PageController` et `PostController` pour traiter les requêtes.
- Routes paramétrées avec contraintes de slug et de numéro.
- Composant de layout Blade partagé, avec les assets chargés par Vite et Tailwind CSS.
- Liste d’articles et pages de détail accessibles avec leurs noms de route.

## Installation et démarrage

Prérequis : PHP 8.3+, Composer, Node.js et npm.

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Ouvrez ensuite <http://localhost:8000>. Pour lancer les outils de développement ensemble, utilisez `composer run dev`.

## Routes disponibles

| Méthode | URL | Nom de route | Description |
| --- | --- | --- | --- |
| GET | `/` | `home` | Page d’accueil Mini-CMS |
| GET | `/posts` | `posts.index` | Liste des articles |
| GET | `/posts/{slug}` | `posts.show` | Détail d’un article (`[a-z0-9-]+`) |
| GET | `/pages/{page}` | `pages.show` | Page avec paramètre numérique |
| GET | `/bonjour` | `bonjour` | Exemple de route vers un contrôleur |
| GET | `/bonjour-court` | `bonjour.court` | Variante de la route bonjour |
| GET | `/bienvenue` | `bienvenue` | Exemple de vue Blade avec données |
| GET | `/version` | `version` | Versions de Laravel et PHP |
| GET | `/heure` | `heure` | Date et heure dans une vue Blade |

Inspectez les routes avec `php artisan route:list`.

## Captures d’écran

Les trois captures demandées pour la session :

### Page d’accueil

![Page d’accueil](screenshots/s03-accueil.png)

### Liste des articles

![Liste des articles](screenshots/s03-articles.png)

### Détail d’un article

![Article Routage, contrôleurs et layouts Blade](screenshots/s03-article.png)
