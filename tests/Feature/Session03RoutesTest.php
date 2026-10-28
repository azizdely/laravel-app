<?php

test('named page routes render their content and navigation', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Bienvenue sur Mini-CMS')
        ->assertSee('Accueil - Mini-CMS')
        ->assertSee(route('posts.index'), false);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Auteur : Aziz Dely')
        ->assertSee('Groupe : MDW32');

    $this->get(route('time'))
        ->assertOk()
        ->assertSee('Heure du serveur')
        ->assertSee(route('time'), false);
});

test('post index and detail pages link to named routes', function () {
    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Découvrir Laravel 13')
        ->assertSee(route('posts.show', 'routes-et-controleurs'), false)
        ->assertSee(route('posts.archive', 2024), false);

    $this->get(route('posts.show', 'routes-et-controleurs'))
        ->assertOk()
        ->assertSee('Routes et contrôleurs')
        ->assertSee('Une route associe une URL à une méthode de contrôleur.')
        ->assertSee(route('posts.index'), false);
});

test('post archive filters by year and rejects invalid or unknown routes', function () {
    $this->get(route('posts.archive', 2024))
        ->assertOk()
        ->assertSee('Premiers pas avec Blade')
        ->assertDontSee('Découvrir Laravel 13');

    $this->get('/posts/archive/abc')->assertNotFound();

    $this->get('/posts/article-inconnu')->assertNotFound();
});
