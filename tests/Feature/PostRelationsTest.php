<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('lists posts with their category, author and tags without lazy loading', function () {
    $category = Category::factory()->create(['name' => 'Tutoriels']);
    $tag = Tag::factory()->create(['name' => 'Astuce']);
    $author = User::factory()->create(['name' => 'Amira Ben Salah']);
    $posts = Post::factory(3)->published()->create([
        'category_id' => $category->id,
        'user_id' => $author->id,
    ]);

    $posts->each(fn (Post $post) => $post->tags()->attach($tag));

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Tutoriels')
        ->assertSee('Amira Ben Salah')
        ->assertSee('Astuce');
});

it('shows relationships for published posts on the home page', function () {
    $category = Category::factory()->create(['name' => 'Accueil']);
    $tag = Tag::factory()->create(['name' => 'Accueil tag']);
    $author = User::factory()->create(['name' => 'Auteur accueil']);
    $posts = Post::factory(3)->published()->create([
        'category_id' => $category->id,
        'user_id' => $author->id,
    ]);

    $posts->each(fn (Post $post) => $post->tags()->attach($tag));

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Accueil')
        ->assertSee('Auteur accueil')
        ->assertSee('Accueil tag');
});

it('shows relationships for published archive posts without lazy loading', function () {
    $category = Category::factory()->create(['name' => 'Archive']);
    $tag = Tag::factory()->create(['name' => 'Archive tag']);
    $author = User::factory()->create(['name' => 'Auteur archive']);
    $posts = Post::factory(3)->published()->create([
        'category_id' => $category->id,
        'user_id' => $author->id,
        'published_at' => '2025-10-15 12:00:00',
    ]);

    $posts->each(fn (Post $post) => $post->tags()->attach($tag));

    $this->get(route('posts.archive', 2025))
        ->assertOk()
        ->assertSee('Archive')
        ->assertSee('Auteur archive')
        ->assertSee('Archive tag');
});

it('keeps posts when their category is deleted', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->create(['category_id' => $category->id]);

    $category->delete();

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
    expect($post->fresh()->category_id)->toBeNull();
});

it('replaces the tags on a post with sync', function () {
    $post = Post::factory()->create();
    $tags = Tag::factory(3)->create();
    $post->tags()->attach([$tags[0]->id, $tags[1]->id]);

    $post->tags()->sync([$tags[1]->id, $tags[2]->id]);

    expect($post->tags()->count())->toBe(2);
    $this->assertDatabaseMissing('post_tag', ['post_id' => $post->id, 'tag_id' => $tags[0]->id]);
    $this->assertDatabaseHas('post_tag', ['post_id' => $post->id, 'tag_id' => $tags[2]->id]);
});

it('removes pivot rows when a post is deleted', function () {
    $post = Post::factory()->create();
    $tag = Tag::factory()->create();
    $post->tags()->attach($tag);

    $this->delete(route('posts.destroy', $post));

    $this->assertDatabaseMissing('post_tag', ['post_id' => $post->id]);
    $this->assertDatabaseHas('tags', ['id' => $tag->id]);
});

it('shows at most three newest published posts from the same category', function () {
    $category = Category::factory()->create();
    $currentPost = Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article actuel',
        'slug' => 'article-actuel',
    ]);
    $newestPost = Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article associe recent',
        'slug' => 'article-associe-recent',
        'published_at' => '2026-01-14 12:00:00',
    ]);
    $middlePost = Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article associe moyen',
        'slug' => 'article-associe-moyen',
        'published_at' => '2026-01-13 12:00:00',
    ]);
    $oldestIncludedPost = Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article associe ancien',
        'slug' => 'article-associe-ancien',
        'published_at' => '2026-01-12 12:00:00',
    ]);
    $outsideLimitPost = Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article associe hors limite',
        'slug' => 'article-associe-hors-limite',
        'published_at' => '2026-01-11 12:00:00',
    ]);
    $draftPost = Post::factory()->create([
        'category_id' => $category->id,
        'title' => 'Brouillon associe',
        'slug' => 'brouillon-associe',
    ]);
    $otherCategoryPost = Post::factory()->published()->create([
        'title' => 'Article autre categorie',
        'slug' => 'article-autre-categorie',
    ]);

    $this->get(route('posts.show', $currentPost))
        ->assertOk()
        ->assertSeeInOrder([
            $newestPost->title,
            $middlePost->title,
            $oldestIncludedPost->title,
        ])
        ->assertDontSee($outsideLimitPost->title)
        ->assertDontSee($draftPost->title)
        ->assertDontSee($otherCategoryPost->title)
        ->assertDontSee('href="'.route('posts.show', $currentPost).'"', false);
});

it('omits related posts when the current post has no category', function () {
    $post = Post::factory()->published()->create();

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertDontSee('Dans la même catégorie');
});

it('omits related posts when the category has no other published posts', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->published()->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertDontSee('Dans la même catégorie');
});
