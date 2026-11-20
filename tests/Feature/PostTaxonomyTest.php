<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

beforeEach(function () {
    $this->withoutVite();
});

function taxonomyPostData(array $overrides = []): array
{
    return array_replace([
        'title' => 'Article classe',
        'slug' => 'article-classe',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '1',
    ], $overrides);
}

it('stores the category and tags chosen in the article form', function () {
    $category = Category::factory()->create();
    $tags = Tag::factory(2)->create();

    $this->post(route('posts.store'), taxonomyPostData([
        'category_id' => $category->id,
        'tags' => $tags->modelKeys(),
    ]))->assertRedirect();

    $post = Post::where('slug', 'article-classe')->firstOrFail();

    expect($post->category_id)->toBe($category->id)
        ->and($post->tags()->pluck('tags.id')->all())->toEqualCanonicalizing($tags->modelKeys());
});

it('replaces article tags with the submitted selection', function () {
    $post = Post::factory()->create(['slug' => 'article-classe']);
    [$removedTag, $retainedTag, $addedTag] = Tag::factory(3)->create()->all();
    $post->tags()->attach([$removedTag->id, $retainedTag->id]);

    $this->put(route('posts.update', $post), taxonomyPostData([
        'tags' => [$retainedTag->id, $addedTag->id],
    ]))->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseMissing('post_tag', ['post_id' => $post->id, 'tag_id' => $removedTag->id]);
    $this->assertDatabaseHas('post_tag', ['post_id' => $post->id, 'tag_id' => $retainedTag->id]);
    $this->assertDatabaseHas('post_tag', ['post_id' => $post->id, 'tag_id' => $addedTag->id]);
});

it('removes all tags when none are selected', function () {
    $post = Post::factory()->create(['slug' => 'article-classe']);
    $post->tags()->attach(Tag::factory(2)->create());

    $this->put(route('posts.update', $post), taxonomyPostData())
        ->assertRedirect(route('posts.show', $post));

    expect($post->tags()->count())->toBe(0);
});

it('rejects category and tag identifiers that do not exist', function () {
    $this->post(route('posts.store'), taxonomyPostData([
        'category_id' => 999,
        'tags' => [999],
    ]))->assertSessionHasErrors(['category_id', 'tags.0']);

    $this->assertDatabaseMissing('posts', ['slug' => 'article-classe']);
});

it('lists only published articles in a category resolved by slug', function () {
    $category = Category::factory()->create(['slug' => 'tutoriels']);
    Post::factory()->published()->create([
        'category_id' => $category->id,
        'title' => 'Article publie de la categorie',
    ]);
    Post::factory()->create([
        'category_id' => $category->id,
        'title' => 'Brouillon de la categorie',
    ]);
    Post::factory()->published()->create(['title' => 'Article hors categorie']);

    $this->get(route('posts.category', $category))
        ->assertOk()
        ->assertSee('Catégorie : '.$category->name)
        ->assertSee('Article publie de la categorie')
        ->assertDontSee('Brouillon de la categorie')
        ->assertDontSee('Article hors categorie');
});

it('returns 404 for an unknown category slug', function () {
    $this->get('/posts/categorie/inconnue')->assertNotFound();
});

it('counts only published articles beside each category on the home page', function () {
    $category = Category::factory()->create(['name' => 'Tutoriels']);
    Post::factory(2)->published()->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Tutoriels (2)');
});

it('lists published articles in a tag resolved by slug', function () {
    $tag = Tag::factory()->create(['slug' => 'laravel']);
    $publishedPost = Post::factory()->published()->create(['title' => 'Article avec le tag']);
    $draftPost = Post::factory()->create(['title' => 'Brouillon avec le tag']);
    Post::factory()->published()->create(['title' => 'Article sans le tag']);
    $publishedPost->tags()->attach($tag);
    $draftPost->tags()->attach($tag);

    $this->get(route('posts.tag', $tag))
        ->assertOk()
        ->assertSee('Tag : '.$tag->name)
        ->assertSee('Article avec le tag')
        ->assertDontSee('Brouillon avec le tag')
        ->assertDontSee('Article sans le tag');
});

it('returns 404 for an unknown tag slug', function () {
    $this->get('/posts/tag/inconnu')->assertNotFound();
});

it('shows popular tags in count order and omits tags with only drafts', function () {
    $popularTag = Tag::factory()->create(['name' => 'Populaire', 'slug' => 'populaire']);
    $otherTag = Tag::factory()->create(['name' => 'Secondaire', 'slug' => 'secondaire']);
    $draftTag = Tag::factory()->create(['name' => 'Brouillon seulement', 'slug' => 'brouillon-seulement']);

    Post::factory(2)->published()->create()->each(fn (Post $post) => $post->tags()->attach($popularTag));
    Post::factory()->published()->create()->tags()->attach($otherTag);
    Post::factory()->create()->tags()->attach($draftTag);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Populaire (2)', 'Secondaire (1)'])
        ->assertDontSee('Brouillon seulement');
});

it('hides the popular tag section when no published post has tags', function () {
    Tag::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Tags populaires');
});
