<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('posts.index', [
            'posts' => array_values($this->posts()),
        ]);
    }

    public function show(string $slug): View
    {
        $posts = $this->posts();

        abort_unless(isset($posts[$slug]), 404);

        return view('posts.show', [
            'post' => $posts[$slug],
        ]);
    }

    /**
     * @return array<string, array{
     *     slug: string,
     *     title: string,
     *     category: string,
     *     published_at: string,
     *     reading_time: string,
     *     excerpt: string,
     *     body: list<string>
     * }>
     */
    private function posts(): array
    {
        return [
            'routes-et-controleurs' => [
                'slug' => 'routes-et-controleurs',
                'title' => 'Routage, contrôleurs et layouts Blade',
                'category' => 'Laravel',
                'published_at' => '28 octobre 2026',
                'reading_time' => '5 min',
                'excerpt' => 'Organiser les URL, déplacer la logique dans les contrôleurs et partager une interface avec les composants Blade.',
                'body' => [
                    'Une route associe une URL à une action. Les routes nommées donnent aux liens un identifiant stable : si l’URL change, les vues qui utilisent route() n’ont pas à être réécrites.',
                    'Un contrôleur regroupe les actions liées à une ressource. Il reçoit les paramètres de la route, prépare les données et les transmet à la vue qui construit la réponse HTML.',
                    'Un composant Blade de mise en page partage les éléments communs aux pages. Son slot principal accueille le contenu, tandis que le slot title permet de personnaliser le titre de chaque page.',
                ],
            ],
            'premiers-pas-avec-blade' => [
                'slug' => 'premiers-pas-avec-blade',
                'title' => 'Premiers pas avec Blade',
                'category' => 'Templates',
                'published_at' => '24 octobre 2026',
                'reading_time' => '4 min',
                'excerpt' => 'Afficher des données échappées et composer des pages lisibles avec les vues Blade.',
                'body' => [
                    'Blade est le moteur de templates intégré à Laravel. Les expressions entre doubles accolades affichent les données après échappement HTML.',
                    'Les composants rendent les interfaces réutilisables. Un composant de layout peut définir l’en-tête et le pied de page une seule fois pour toutes les pages.',
                ],
            ],
            'decouvrir-les-routes' => [
                'slug' => 'decouvrir-les-routes',
                'title' => 'Découvrir les routes Laravel',
                'category' => 'Routage',
                'published_at' => '20 octobre 2026',
                'reading_time' => '3 min',
                'excerpt' => 'Définir des URL claires, valider leurs paramètres et générer des liens à partir de leurs noms.',
                'body' => [
                    'Le fichier routes/web.php rassemble les routes de l’interface web. Une route peut appeler un contrôleur et transmettre les paramètres de son URI à une méthode.',
                    'Les contraintes comme whereNumber et where limitent les valeurs acceptées. Si un paramètre ne respecte pas sa contrainte, Laravel répond avec une page 404.',
                ],
            ],
        ];
    }
}
