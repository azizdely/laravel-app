<?php

use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

it('shows a paginated list of articles', function () {
    foreach (range(1, 5) as $number) {
        Post::create([
            'title' => "Article {$number}",
            'slug' => "article-{$number}",
            'body' => "Contenu pédagogique de l'article {$number}.",
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Article 5')
        ->assertDontSee('Article 1')
        ->assertSee('page=2', false);
});

it('shows a message when there are no articles', function () {
    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Aucun article');
});

it('shows an article and escapes its title', function () {
    $post = Post::create([
        'title' => '<script>alert("x")</script>',
        'slug' => 'article-complet',
        'body' => 'Texte complet de cet article de test.',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>', false)
        ->assertSee('Texte complet de cet article de test.');
});

it('returns 404 when an article slug does not exist', function () {
    $this->get('/posts/article-inconnu')->assertNotFound();
});

it('shows the article creation form', function () {
    $this->get(route('posts.create'))
        ->assertOk()
        ->assertSee('Nouvel article')
        ->assertSee(route('posts.store'), false)
        ->assertSee('name="_token"', false);
});

it('stores a new article as a draft when publishing is not requested', function () {
    $response = $this->post(route('posts.store'), [
        'title' => 'Premier article créé',
        'slug' => 'premier-article-cree',
        'body' => 'Contenu suffisamment long pour respecter la validation.',
    ]);

    $response
        ->assertRedirect(route('posts.show', 'premier-article-cree'))
        ->assertSessionHas('status', 'Article créé.');

    $this->assertDatabaseHas('posts', [
        'title' => 'Premier article créé',
        'slug' => 'premier-article-cree',
        'is_published' => false,
        'published_at' => null,
    ]);
});

it('publishes a valid article and records its publication time', function () {
    $this->post(route('posts.store'), [
        'title' => 'Article publié',
        'slug' => 'article-publie',
        'body' => 'Contenu suffisamment long pour publier cet article.',
        'is_published' => '1',
    ])->assertRedirect(route('posts.show', 'article-publie'));

    $post = Post::where('slug', 'article-publie')->firstOrFail();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at)->not->toBeNull();
});

it('rejects missing article fields without saving a record', function () {
    $this->post(route('posts.store'), [])
        ->assertSessionHasErrors(['title', 'slug', 'body']);

    $this->assertDatabaseCount('posts', 0);
});

it('rejects an invalid slug and a body that is too short', function () {
    $this->post(route('posts.store'), [
        'title' => 'Article invalide',
        'slug' => 'Article invalide',
        'body' => 'Trop court.',
    ])->assertSessionHasErrors(['slug', 'body']);

    $this->assertDatabaseCount('posts', 0);
});

it('rejects a slug that is already in use', function () {
    Post::create([
        'title' => 'Article existant',
        'slug' => 'slug-deja-utilise',
        'body' => 'Contenu de l’article déjà présent dans la base.',
        'is_published' => false,
    ]);

    $this->post(route('posts.store'), [
        'title' => 'Article en double',
        'slug' => 'slug-deja-utilise',
        'body' => 'Contenu suffisamment long pour le nouvel article.',
    ])->assertSessionHasErrors('slug');

    $this->assertDatabaseCount('posts', 1);
});

it('shows only the three most recently published articles on the home page', function () {
    Post::create([
        'title' => 'Article publié le plus ancien',
        'slug' => 'ancien-publie',
        'body' => 'Cet article publié est plus ancien que les trois autres.',
        'is_published' => true,
        'published_at' => now()->subDays(4),
    ]);

    Post::create([
        'title' => 'Troisième article récent',
        'slug' => 'troisieme-recent',
        'body' => 'Cet article fait partie des trois plus récents.',
        'is_published' => true,
        'published_at' => now()->subDays(3),
    ]);

    Post::create([
        'title' => 'Deuxième article récent',
        'slug' => 'deuxieme-recent',
        'body' => 'Cet article fait partie des trois plus récents.',
        'is_published' => true,
        'published_at' => now()->subDays(2),
    ]);

    Post::create([
        'title' => 'Article le plus récent',
        'slug' => 'plus-recent',
        'body' => 'Cet article est le plus récent de la page d’accueil.',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    Post::create([
        'title' => 'Brouillon absent de l’accueil',
        'slug' => 'brouillon-accueil',
        'body' => 'Un brouillon ne doit pas apparaître sur la page d’accueil.',
        'is_published' => false,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder([
            'Article le plus récent',
            'Deuxième article récent',
            'Troisième article récent',
        ])
        ->assertDontSee('Article publié le plus ancien')
        ->assertDontSee('Brouillon absent de l’accueil');
});
