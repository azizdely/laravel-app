<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

it('lists categories with published post counts', function () {
    $category = Category::factory()->create(['name' => 'Frameworks']);
    Post::factory()->published()->count(2)->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonStructure(['data' => ['*' => ['id', 'name', 'slug', 'posts_count']]])
        ->assertJsonPath('data.0.posts_count', 2);
});

it('returns a category with its published post count', function () {
    $category = Category::factory()->create();
    Post::factory()->published()->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->getJson('/api/categories/'.$category->id)
        ->assertOk()
        ->assertJsonPath('data.id', $category->id)
        ->assertJsonPath('data.posts_count', 1);
});

it('returns 404 for an unknown category', function () {
    $this->getJson('/api/categories/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

it('filters published posts by category slug', function () {
    $category = Category::factory()->create(['slug' => 'frameworks']);
    $matchingPost = Post::factory()->published()->create(['category_id' => $category->id]);
    Post::factory()->published()->create();

    $this->getJson('/api/posts?category=frameworks')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingPost->id);
});

it('returns 422 for an unknown category filter', function () {
    $this->getJson('/api/posts?category=inconnue')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category'])
        ->assertJsonPath('errors.category.0', 'La valeur sélectionnée pour le champ catégorie est invalide.');
});

it('lists tags with published post counts', function () {
    $tag = Tag::factory()->create(['name' => 'Laravel']);
    $publishedPost = Post::factory()->published()->create();
    $publishedPost->tags()->attach($tag);
    $draft = Post::factory()->create();
    $draft->tags()->attach($tag);

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonStructure(['data' => ['*' => ['id', 'name', 'slug', 'posts_count']]])
        ->assertJsonPath('data.0.posts_count', 1);
});

it('returns a tag with its published post count', function () {
    $tag = Tag::factory()->create();
    $post = Post::factory()->published()->create();
    $post->tags()->attach($tag);

    $this->getJson('/api/tags/'.$tag->id)
        ->assertOk()
        ->assertJsonPath('data.id', $tag->id)
        ->assertJsonPath('data.posts_count', 1);
});

it('returns 404 for an unknown tag', function () {
    $this->getJson('/api/tags/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

it('filters published posts by tag slug', function () {
    $tag = Tag::factory()->create(['slug' => 'laravel']);
    $matchingPost = Post::factory()->published()->create();
    $matchingPost->tags()->attach($tag);
    Post::factory()->published()->create();

    $this->getJson('/api/posts?tag=laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $matchingPost->id);
});

it('returns 422 for an unknown tag filter', function () {
    $this->getJson('/api/posts?tag=inconnu')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['tag']);
});

it('preserves filters when paginating posts', function () {
    $category = Category::factory()->create(['slug' => 'frameworks']);
    Post::factory()->published()->count(2)->create(['category_id' => $category->id]);

    $this->getJson('/api/posts?category=frameworks&per_page=1')
        ->assertOk()
        ->assertJsonPath('links.next', fn (string $url) => str_contains($url, 'category=frameworks'));
});
