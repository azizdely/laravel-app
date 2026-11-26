<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function apiPostData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Article API',
        'slug' => 'article-api',
        'excerpt' => 'Un extrait pour l API.',
        'body' => 'Le contenu complet de cet article envoyé avec un jeton Sanctum.',
        'is_published' => true,
    ], $overrides);
}

it('returns 401 when creating a post without a token', function () {
    $this->postJson('/api/posts', apiPostData())
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Unauthenticated.');

    $this->assertDatabaseCount('posts', 0);
});

it('creates a post for the authenticated token owner', function () {
    $author = User::factory()->create();
    $tag = Tag::factory()->create();
    Sanctum::actingAs($author);

    $this->postJson('/api/posts', apiPostData(['tags' => [$tag->id]]))
        ->assertCreated()
        ->assertJsonPath('data.slug', 'article-api')
        ->assertJsonPath('data.author.id', $author->id)
        ->assertJsonPath('data.tags.0.id', $tag->id);

    $post = Post::where('slug', 'article-api')->firstOrFail();

    $this->assertDatabaseHas('posts', [
        'slug' => 'article-api',
        'user_id' => $author->id,
        'is_published' => true,
    ]);
    $this->assertDatabaseHas('post_tag', [
        'post_id' => $post->id,
        'tag_id' => $tag->id,
    ]);
});

it('returns French validation errors for an invalid post', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/posts', apiPostData(['title' => '']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title'])
        ->assertJsonPath('errors.title.0', 'Le champ titre est obligatoire.');
});

it('returns 401 when updating a post without a token', function () {
    $post = Post::factory()->create();

    $this->putJson('/api/posts/'.$post->slug, apiPostData())
        ->assertUnauthorized();
});

it('returns 403 before validating another authors post', function () {
    $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);
    Sanctum::actingAs(User::factory()->create());

    $this->putJson('/api/posts/'.$post->slug, ['title' => ''])
        ->assertForbidden();
});

it('updates an owned post and synchronizes its tags', function () {
    $author = User::factory()->create();
    $oldTag = Tag::factory()->create();
    $newTag = Tag::factory()->create();
    $post = Post::factory()->published()->for($author)->create();
    $post->tags()->attach($oldTag);
    Sanctum::actingAs($author);

    $this->putJson('/api/posts/'.$post->slug, apiPostData([
        'slug' => 'article-modifie',
        'tags' => [$newTag->id],
    ]))
        ->assertOk()
        ->assertJsonPath('data.slug', 'article-modifie')
        ->assertJsonCount(1, 'data.tags')
        ->assertJsonPath('data.tags.0.id', $newTag->id);

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'slug' => 'article-modifie',
        'user_id' => $author->id,
    ]);
    $this->assertDatabaseMissing('post_tag', [
        'post_id' => $post->id,
        'tag_id' => $oldTag->id,
    ]);
    $this->assertDatabaseHas('post_tag', [
        'post_id' => $post->id,
        'tag_id' => $newTag->id,
    ]);
});

it('returns 204 when the author deletes their post', function () {
    $author = User::factory()->create();
    $post = Post::factory()->for($author)->create();
    Sanctum::actingAs($author);

    $this->deleteJson('/api/posts/'.$post->slug)->assertNoContent();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('issues a usable Sanctum token on login', function () {
    $user = User::factory()->create();

    $token = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->json('token');

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->withToken($token)
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('email', $user->email);
});

it('returns 422 for invalid API login credentials', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'incorrect',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('errors.email.0', 'Ces identifiants ne correspondent à aucun compte.');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

it('returns 401 when logout has no bearer token', function () {
    $this->actingAs(User::factory()->create())
        ->postJson('/api/logout')
        ->assertUnauthorized();
});

it('revokes the current token on logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertNoContent();

    $this->assertDatabaseCount('personal_access_tokens', 0);

    $this->app['auth']->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/user')
        ->assertUnauthorized();
});
