<?php

use App\Models\Post;

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
    $post = Post::create([
        'title' => 'Routes et contrôleurs',
        'slug' => 'routes-et-controleurs',
        'body' => 'Une route associe une URL à une méthode de contrôleur.',
        'is_published' => true,
        'published_at' => now()->setDate(2024, 11, 18),
    ]);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee(route('posts.show', $post), false)
        ->assertSee(route('posts.archive', 2024), false);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Routes et contrôleurs')
        ->assertSee('Une route associe une URL à une méthode de contrôleur.')
        ->assertSee(route('posts.index'), false);
});

test('post archive filters by year and rejects invalid or unknown routes', function () {
    Post::create([
        'title' => 'Article de 2024',
        'slug' => 'article-de-2024',
        'body' => 'Un article publié en 2024 pour vérifier les archives.',
        'is_published' => true,
        'published_at' => now()->setDate(2024, 10, 30),
    ]);

    Post::create([
        'title' => 'Article de 2026',
        'slug' => 'article-de-2026',
        'body' => 'Un article publié en 2026 pour vérifier les archives.',
        'is_published' => true,
        'published_at' => now()->setDate(2026, 9, 1),
    ]);

    $this->get(route('posts.archive', 2024))
        ->assertOk()
        ->assertSee('Article de 2024')
        ->assertDontSee('Article de 2026');

    $this->get('/posts/archive/abc')->assertNotFound();

    $this->get('/posts/article-inconnu')->assertNotFound();
});
