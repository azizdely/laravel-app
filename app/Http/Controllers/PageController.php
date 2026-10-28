<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function bonjour(): string
    {
        return 'Bonjour MDW3 ! Voici ma première route Laravel 13.';
    }

    public function bonjourCourt(): string
    {
        return 'Même résultat, écrit avec une fonction fléchée.';
    }

    public function bienvenue(): View
    {
        return view('bienvenue', [
            'etudiant' => 'aziz dely',
            'groupe' => 'MDW32',
            'cours' => 'Atelier Framework Côté Serveur',
        ]);
    }

    public function version(): string
    {
        return 'Laravel '.app()->version().' - PHP '.PHP_VERSION;
    }

    public function heure(): View
    {
        return view('heure', [
            'heure' => now()->format('H:i'),
            'date' => now()->format('d/m/Y'),
        ]);
    }

    public function show(int $page): View
    {
        abort_unless(in_array($page, [1, 2, 3], true), 404);

        return view('pages.show', [
            'pageNumber' => $page,
            'pageTitle' => [
                1 => 'Bienvenue',
                2 => 'Les routes Laravel',
                3 => 'Les contrôleurs et Blade',
            ][$page],
        ]);
    }
}
