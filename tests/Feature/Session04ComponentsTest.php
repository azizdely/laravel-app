<?php

use App\Models\Post;

it('reconstructs the article pages with reusable components', function () {
    $publishedPost = Post::create([
        'title' => 'Routes et contrôleurs',
        'slug' => 'routes-et-controleurs',
        'body' => 'Suivre une requête depuis son URL jusqu’au HTML affiché.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    Post::create([
        'title' => 'Article en brouillon',
        'slug' => 'article-en-brouillon',
        'body' => 'Cet article est conservé comme brouillon.',
        'is_published' => false,
    ]);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Tous les articles')
        ->assertSee('Brouillon')
        ->assertSee(route('posts.show', $publishedPost), false)
        ->assertSee('aria-current="page"', false);

    $this->get(route('posts.show', $publishedPost))
        ->assertOk()
        ->assertSee('Routes et contrôleurs')
        ->assertSee('Publié le')
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
        ->assertSee('Le projet fil rouge')
        ->assertDontSee('Sprint en cours')
        ->assertSee('aria-current="page"', false);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertDontSee('Le projet fil rouge')
        ->assertSee(route('posts.create'), false);
});

it('shows an empty state for an archive without articles', function () {
    $this->get(route('posts.archive', 2025))
        ->assertOk()
        ->assertSee('Articles de 2025')
        ->assertSee('Aucun article');

    $this->get('/posts/unknown-slug')->assertNotFound();
});
