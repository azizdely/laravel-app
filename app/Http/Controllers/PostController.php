<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * @return array<string, array{
     *     slug: string,
     *     title: string,
     *     year: int,
     *     author: string,
     *     published_at: string,
     *     featured: bool,
     *     summary: string,
     *     body: list<string>
     * }>
     */
    private function posts(): array
    {
        return [
            'decouvrir-laravel-13' => [
                'slug' => 'decouvrir-laravel-13',
                'title' => 'Découvrir Laravel 13',
                'year' => 2026,
                'author' => 'Aziz Dely',
                'published_at' => '2026-10-28',
                'featured' => true,
                'summary' => 'Un premier aperçu des outils et des conventions qui rendent Laravel agréable à utiliser.',
                'body' => [
                    'Laravel 13 simplifie la structure du projet : la configuration des middlewares et des exceptions se fait dans bootstrap/app.php.',
                    'Le framework fournit des conventions utiles pour organiser les routes, les contrôleurs, les modèles et les vues.',
                ],
            ],
            'routes-et-controleurs' => [
                'slug' => 'routes-et-controleurs',
                'title' => 'Routes et contrôleurs',
                'year' => 2026,
                'author' => 'Aziz Dely',
                'published_at' => '2026-10-24',
                'featured' => false,
                'summary' => 'Suivre une requête depuis son URL jusqu’au HTML affiché.',
                'body' => [
                    'Une route associe une URL à une méthode de contrôleur. Le contrôleur prépare les données, la vue les affiche.',
                    'Les noms de routes gardent les liens stables et les composants Blade évitent de répéter la même interface.',
                ],
            ],
            'premiers-pas-avec-blade' => [
                'slug' => 'premiers-pas-avec-blade',
                'title' => 'Premiers pas avec Blade',
                'year' => 2024,
                'author' => 'Aziz Dely',
                'published_at' => '2024-11-18',
                'featured' => false,
                'summary' => 'Créer des pages dynamiques tout en gardant les données affichées échappées.',
                'body' => [
                    'Blade est le moteur de vues de Laravel. Les doubles accolades échappent toujours le contenu affiché.',
                    'Les composants permettent de réutiliser des éléments d’interface comme les alertes, la navigation et les cartes.',
                ],
            ],
            'organiser-ses-vues' => [
                'slug' => 'organiser-ses-vues',
                'title' => 'Organiser ses vues',
                'year' => 2024,
                'author' => 'Aziz Dely',
                'published_at' => '2024-10-30',
                'featured' => false,
                'summary' => 'Partager une structure de page et composer des listes et des détails réutilisables.',
                'body' => [
                    'Un layout Blade centralise les éléments communs aux pages comme l’en-tête, la navigation et le pied de page.',
                    'Les pages conservent leur contenu spécifique dans des vues qui utilisent ce layout.',
                ],
            ],
        ];
    }

    public function index(): View
    {
        return view('posts.index', [
            'heading' => 'Tous les articles',
            'posts' => $this->posts(),
        ]);
    }

    public function archive(string $year): View
    {
        $posts = array_filter(
            $this->posts(),
            fn (array $post): bool => $post['year'] === (int) $year,
        );

        return view('posts.index', [
            'heading' => 'Articles de '.$year,
            'posts' => $posts,
        ]);
    }

    public function show(string $slug): View
    {
        $posts = $this->posts();

        abort_unless(array_key_exists($slug, $posts), 404);

        return view('posts.show', ['post' => $posts[$slug]]);
    }
}
