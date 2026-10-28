<?php

it('renders the home page and links to the article list', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Bienvenue sur Mini-CMS')
        ->assertSee(route('posts.index'));
});

it('lists articles and opens the requested article', function () {
    $response = $this->get('/posts');

    $response
        ->assertOk()
        ->assertSee('Tous les articles')
        ->assertSee('Routage, contrôleurs et layouts Blade')
        ->assertSee(route('posts.show', ['slug' => 'routes-et-controleurs']));

    $articleResponse = $this->get('/posts/routes-et-controleurs');

    $articleResponse
        ->assertOk()
        ->assertSee('Une route associe une URL à une action.')
        ->assertSee('Tous les articles');
});

it('returns not found for an unknown article slug', function () {
    $response = $this->get('/posts/article-inconnu');

    $response->assertNotFound();
});

it('rejects article slugs that do not match the route constraint', function () {
    $response = $this->get('/posts/Slug_Invalide');

    $response->assertNotFound();
});

it('renders a page for a numeric page parameter', function () {
    $response = $this->get('/pages/2');

    $response
        ->assertOk()
        ->assertSee('Les routes Laravel');
});

it('rejects page parameters that are not numeric', function () {
    $response = $this->get('/pages/deux');

    $response->assertNotFound();
});

it('keeps the existing greeting and information routes available', function () {
    $this->get('/bonjour')
        ->assertOk()
        ->assertSee('Bonjour MDW3 ! Voici ma première route Laravel 13.');

    $this->get('/bonjour-court')
        ->assertOk()
        ->assertSee('Même résultat, écrit avec une fonction fléchée.');

    $this->get('/bienvenue')
        ->assertOk()
        ->assertSee('aziz dely');

    $this->get('/heure')
        ->assertOk()
        ->assertSee(now()->format('d/m/Y'));

    $this->get('/version')
        ->assertOk()
        ->assertSee(app()->version());
});
