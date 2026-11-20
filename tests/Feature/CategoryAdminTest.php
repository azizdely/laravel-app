<?php

use App\Models\Category;
use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

it('lists categories alphabetically with their post counts', function () {
    $second = Category::factory()->create(['name' => 'Zèbre']);
    $first = Category::factory()->create(['name' => 'Articles']);
    Post::factory(2)->create(['category_id' => $first->id]);
    Post::factory()->create(['category_id' => $second->id]);

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSeeInOrder(['Articles', 'Zèbre'])
        ->assertSee('2')
        ->assertSee('1');
});

it('creates a category and shows a success message', function () {
    $this->followingRedirects()
        ->post(route('admin.categories.store'), ['name' => 'Laravel 13', 'slug' => 'laravel-13'])
        ->assertOk()
        ->assertSee('Catégorie créée.');

    $this->assertDatabaseHas('categories', ['name' => 'Laravel 13', 'slug' => 'laravel-13']);
});

it('rejects an empty category with French validation errors', function () {
    $this->from(route('admin.categories.create'))
        ->post(route('admin.categories.store'), ['name' => '', 'slug' => ''])
        ->assertRedirect(route('admin.categories.create'))
        ->assertSessionHasErrors([
            'name' => 'Le champ nom est obligatoire.',
            'slug' => 'Le champ slug est obligatoire.',
        ]);
});

it('rejects a category slug already used by another category', function () {
    Category::factory()->create(['name' => 'PHP', 'slug' => 'php']);

    $this->post(route('admin.categories.store'), ['name' => 'Laravel', 'slug' => 'php'])
        ->assertSessionHasErrors(['slug' => 'Ce slug est déjà utilisé par une autre catégorie.']);
});

it('updates a category without treating its current slug as a duplicate', function () {
    $category = Category::factory()->create(['name' => 'Ancien nom']);

    $this->put(route('admin.categories.update', $category), [
        'name' => 'Nouveau nom',
        'slug' => $category->slug,
    ])->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('success', 'Catégorie mise à jour.');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Nouveau nom',
        'slug' => $category->slug,
    ]);
});

it('deletes a category and leaves its posts without a category', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->create(['category_id' => $category->id]);

    $this->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('success', 'Catégorie supprimée.');

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'category_id' => null]);
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('blocks category writes and allows reads when the site is read-only', function () {
    config(['site.read_only' => true]);

    $this->get(route('admin.categories.index'))->assertOk();

    $this->post(route('admin.categories.store'), ['name' => 'Laravel', 'slug' => 'laravel'])
        ->assertStatus(503)
        ->assertSee('Le site est en lecture seule.');

    $this->assertDatabaseCount('categories', 0);
});
