<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

beforeEach(function () {
    $this->withoutVite();
});

it('returns only published posts as paginated resources', function () {
    $category = Category::factory()->create(['name' => 'Frameworks']);
    $tag = Tag::factory()->create(['name' => 'Laravel']);
    $post = Post::factory()->published()->for($category)->create([
        'title' => 'Article public API',
        'excerpt' => 'Résumé public',
    ]);
    $post->tags()->attach($tag);
    Post::factory()->create(['title' => 'Brouillon privé']);

    $this->getJson(route('api.posts.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Article public API')
        ->assertJsonPath('data.0.author.id', $post->user_id)
        ->assertJsonPath('data.0.category.slug', $category->slug)
        ->assertJsonPath('data.0.tags.0.slug', $tag->slug)
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonMissing(['title' => 'Brouillon privé']);
});

it('returns a published post resource by slug', function () {
    $post = Post::factory()->published()->create(['slug' => 'article-public-api']);

    $this->getJson(route('api.posts.show', $post))
        ->assertOk()
        ->assertJsonPath('data.id', $post->id)
        ->assertJsonPath('data.slug', 'article-public-api')
        ->assertJsonPath('data.body', $post->body);
});

it('returns 403 when a guest requests a draft post', function () {
    $post = Post::factory()->create(['is_published' => false]);

    $this->getJson(route('api.posts.show', $post))
        ->assertForbidden()
        ->assertJsonStructure(['message']);
});

it('returns 404 for an unknown post slug', function () {
    $this->getJson('/api/posts/slug-inconnu')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

it('rejects pagination values outside the supported per-page range', function (int $perPage) {
    $this->getJson(route('api.posts.index', ['per_page' => $perPage]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('per_page');
})->with([
    'below the minimum' => 0,
    'above the maximum' => 51,
]);

it('accepts the per-page boundaries', function (int $perPage) {
    $this->getJson(route('api.posts.index', ['per_page' => $perPage]))
        ->assertOk()
        ->assertJsonPath('meta.per_page', $perPage);
})->with([1, 50]);

it('rejects non-positive page numbers', function () {
    $this->getJson(route('api.posts.index', ['page' => 0]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('page');
});
