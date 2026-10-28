<?php

use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

function postData(array $overrides = []): array
{
    return array_replace([
        'title' => 'Article de test',
        'slug' => 'article-de-test',
        'excerpt' => 'Un extrait court.',
        'body' => 'Contenu assez long pour passer la validation.',
        'is_published' => '0',
    ], $overrides);
}

it('creates an article from the form', function () {
    $this->post(route('posts.store'), postData())
        ->assertRedirect(route('posts.show', 'article-de-test'));

    $this->assertDatabaseHas('posts', [
        'title' => 'Article de test',
        'slug' => 'article-de-test',
        'excerpt' => 'Un extrait court.',
        'is_published' => false,
        'published_at' => null,
    ]);
});

it('does not create an article without a title', function () {
    $this->post(route('posts.store'), postData(['title' => '']))
        ->assertSessionHasErrors('title');

    $this->assertDatabaseMissing('posts', ['slug' => 'article-de-test']);
});

it('rejects an excerpt longer than 255 characters', function () {
    $this->post(route('posts.store'), postData(['excerpt' => str_repeat('a', 256)]))
        ->assertSessionHasErrors('excerpt');

    $this->assertDatabaseCount('posts', 0);
});

it('shows the edit form with current article values', function () {
    $post = Post::create(postData(['title' => 'Titre initial']));

    $this->get(route('posts.edit', $post))
        ->assertOk()
        ->assertSee("Modifier l'article")
        ->assertSee('Titre initial')
        ->assertSee('Un extrait court.');
});

it('keeps the existing publication date when updating a published article', function () {
    $publishedAt = now()->subDay()->startOfSecond();
    $post = Post::create(postData([
        'is_published' => true,
        'published_at' => $publishedAt,
    ]));

    $this->put(route('posts.update', $post), postData([
        'is_published' => '1',
    ]))->assertRedirect(route('posts.show', $post));

    expect($post->refresh()->published_at->equalTo($publishedAt))->toBeTrue();
});

it('updates an article without treating its current slug as a duplicate', function () {
    $post = Post::create(postData(['title' => 'Titre initial']));

    $this->put(route('posts.update', $post), postData([
        'title' => 'Titre modifié',
        'excerpt' => 'Extrait modifié.',
    ]))->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Titre modifié',
        'slug' => 'article-de-test',
        'excerpt' => 'Extrait modifié.',
    ]);
});

it('rejects an article update that reuses another article slug', function () {
    $firstPost = Post::create(postData());
    $secondPost = Post::create(postData([
        'title' => 'Deuxième article',
        'slug' => 'deuxieme-article',
    ]));

    $this->put(route('posts.update', $firstPost), postData([
        'slug' => $secondPost->slug,
    ]))->assertSessionHasErrors('slug');

    $this->assertDatabaseHas('posts', [
        'id' => $firstPost->id,
        'slug' => 'article-de-test',
    ]);
});

it('deletes only the requested article', function () {
    $postToDelete = Post::create(postData());
    $remainingPost = Post::create(postData([
        'title' => 'Article conservé',
        'slug' => 'article-conserve',
    ]));

    $this->delete(route('posts.destroy', $postToDelete))
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', ['id' => $postToDelete->id]);
    $this->assertDatabaseHas('posts', ['id' => $remainingPost->id]);
});

it('publishes a draft and clears its publication date when unpublished', function () {
    $post = Post::create(postData());

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Publier')
        ->assertSee('name="_method" value="PATCH"', false)
        ->assertSee('Confirmer la suppression')
        ->assertSee('name="_method" value="DELETE"', false);

    $this->patch(route('posts.publication', $post))
        ->assertRedirect(route('posts.show', $post));

    $post->refresh();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at)->not->toBeNull();

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Dépublier');

    $this->patch(route('posts.publication', $post))
        ->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'is_published' => false,
        'published_at' => null,
    ]);
});

it('returns only published articles from the published scope', function () {
    $publishedPost = Post::create(postData([
        'title' => 'Article publié',
        'slug' => 'article-publie',
        'is_published' => true,
        'published_at' => now(),
    ]));

    Post::create(postData([
        'title' => 'Article brouillon',
        'slug' => 'article-brouillon',
    ]));

    expect(Post::published()->pluck('id')->all())->toBe([$publishedPost->id]);
});
