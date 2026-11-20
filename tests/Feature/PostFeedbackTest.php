<?php

use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

function feedbackPostData(array $overrides = []): array
{
    return array_replace([
        'title' => 'Article avec message',
        'slug' => 'article-avec-message',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '1',
    ], $overrides);
}

it('shows a success message after creating a post', function () {
    $this->followingRedirects()
        ->post(route('posts.store'), feedbackPostData())
        ->assertOk()
        ->assertSee('Article créé.')
        ->assertSee('role="status"', false);
});

it('shows a success message after deleting a post', function () {
    $post = Post::factory()->create();

    $this->followingRedirects()
        ->delete(route('posts.destroy', $post))
        ->assertOk()
        ->assertSee('Article supprimé.');
});

it('shows the custom page for an unknown post', function () {
    $this->get('/posts/999')
        ->assertNotFound()
        ->assertSee('Article introuvable');
});

it('keeps the standard 404 page for an unknown category', function () {
    $this->get('/posts/categorie/inconnue')
        ->assertNotFound()
        ->assertDontSee('Article introuvable');
});

it('allows reads but rejects writes when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->get(route('posts.index'))->assertOk();

    $this->post(route('posts.store'), feedbackPostData())->assertStatus(503);

    $this->assertDatabaseCount('posts', 0);
});

it('shows a read-only banner on the home page', function () {
    config(['site.read_only' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Site en lecture seule');
});

it('shows recent published posts but no drafts on the custom 404 page', function () {
    Post::factory()->published()->create(['title' => 'Article récent publié']);
    Post::factory()->create(['title' => 'Brouillon récent']);

    $this->get('/posts/999')
        ->assertNotFound()
        ->assertSee('Articles récents')
        ->assertSee('Article récent publié')
        ->assertDontSee('Brouillon récent');
});

it('hides the recent posts section when no post has been published', function () {
    $this->get('/posts/999')
        ->assertNotFound()
        ->assertDontSee('Articles récents');
});
