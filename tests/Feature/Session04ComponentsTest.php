<?php

it('reconstructs the article pages with reusable components', function () {
    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Tous les articles')
        ->assertSee('À la une')
        ->assertSee(route('posts.show', 'routes-et-controleurs'), false)
        ->assertSee('aria-current="page"', false);

    $this->get(route('posts.show', 'routes-et-controleurs'))
        ->assertOk()
        ->assertSee('Routes et contrôleurs')
        ->assertSee('Aziz Dely')
        ->assertSee('Retour aux articles')
        ->assertSee(route('posts.index'), false)
        ->assertSee('aria-current="page"', false);
});

it('shows accessible alert variants and shared page headers', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Projet pédagogique')
        ->assertSee('Données provisoires')
        ->assertSee('role="alert"', false);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Sprint en cours')
        ->assertSee('Le projet fil rouge')
        ->assertSee('aria-current="page"', false);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertDontSee('Le projet fil rouge');
});

it('shows an empty state for an archive without articles', function () {
    $this->get(route('posts.archive', 2025))
        ->assertOk()
        ->assertSee('Articles de 2025')
        ->assertSee('Aucun article');

    $this->get('/posts/unknown-slug')->assertNotFound();
});
