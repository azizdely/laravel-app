<?php

use App\Models\Category;

beforeEach(function () {
    $this->withoutVite();
});

it('shows the branded 404 page for an unknown route', function () {
    $this->get('/cette-page-nexiste-pas')
        ->assertNotFound()
        ->assertSee('Page introuvable')
        ->assertSee('Retour à');
});

it('shows the branded 503 page when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->post(route('posts.store'), [
        'title' => 'Article refusé',
        'slug' => 'article-refuse',
        'body' => 'Contenu assez long pour passer la validation.',
    ])->assertStatus(503)
        ->assertSee('Service momentanément indisponible')
        ->assertSee('Le site est en lecture seule.');
});

it('shows category suggestions when a category slug is unknown', function () {
    $category = Category::factory()->create(['name' => 'Tutoriels', 'slug' => 'tutoriels']);

    $this->get('/posts/categorie/inconnue')
        ->assertNotFound()
        ->assertSee('Catégorie introuvable')
        ->assertSee('Tutoriels')
        ->assertSee(route('posts.category', ['category' => $category->slug]), false);
});
