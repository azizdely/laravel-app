<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
    $this->actingAs(User::factory()->admin()->create());
});

it('lists tags alphabetically with their post counts', function () {
    $second = Tag::factory()->create(['name' => 'Zèbre']);
    $first = Tag::factory()->create(['name' => 'Articles']);
    Post::factory(2)->create()->each(fn (Post $post) => $post->tags()->attach($first));
    Post::factory()->create()->tags()->attach($second);

    $this->get(route('admin.tags.index'))
        ->assertOk()
        ->assertSeeInOrder(['Articles', 'Zèbre'])
        ->assertSee('2')
        ->assertSee('1');
});

it('creates a tag and shows a success message', function () {
    $this->followingRedirects()
        ->post(route('admin.tags.store'), ['name' => 'Laravel', 'slug' => 'laravel'])
        ->assertOk()
        ->assertSee('Tag créé.');

    $this->assertDatabaseHas('tags', ['name' => 'Laravel', 'slug' => 'laravel']);
});

it('shows French validation errors for invalid tag data', function () {
    $this->from(route('admin.tags.create'))
        ->post(route('admin.tags.store'), ['name' => '', 'slug' => ''])
        ->assertRedirect(route('admin.tags.create'))
        ->assertSessionHasErrors([
            'name' => 'Le champ nom est obligatoire.',
            'slug' => 'Le champ slug est obligatoire.',
        ]);
});

it('rejects a tag slug already used by another tag', function () {
    Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->post(route('admin.tags.store'), ['name' => 'Laravel', 'slug' => 'php'])
        ->assertSessionHasErrors(['slug' => 'Ce slug est déjà utilisé par un autre tag.']);
});

it('updates a tag without treating its current slug as a duplicate', function () {
    $tag = Tag::factory()->create(['name' => 'Ancien nom']);

    $this->put(route('admin.tags.update', $tag), [
        'name' => 'Nouveau nom',
        'slug' => $tag->slug,
    ])->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('success', 'Tag mis à jour.');

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Nouveau nom',
        'slug' => $tag->slug,
    ]);
});

it('deletes a tag and detaches it from posts', function () {
    $tag = Tag::factory()->create();
    $post = Post::factory()->create();
    $post->tags()->attach($tag);

    $this->delete(route('admin.tags.destroy', $tag))
        ->assertRedirect(route('admin.tags.index'))
        ->assertSessionHas('success', 'Tag supprimé.');

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    $this->assertDatabaseMissing('post_tag', ['post_id' => $post->id, 'tag_id' => $tag->id]);
});

it('blocks tag writes and allows reads when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->get(route('admin.tags.index'))->assertOk();

    $this->post(route('admin.tags.store'), ['name' => 'Laravel', 'slug' => 'laravel'])
        ->assertStatus(503);

    $this->assertDatabaseCount('tags', 0);
});
