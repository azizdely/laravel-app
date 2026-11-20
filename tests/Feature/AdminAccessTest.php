<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->withoutVite();
});

it('redirects a guest from the back office to login', function () {
    $this->get(route('admin.tags.index'))
        ->assertRedirect(route('login'));
});

it('forbids an author from managing categories and tags', function () {
    $author = User::factory()->create();

    $this->actingAs($author)
        ->get(route('admin.categories.index'))
        ->assertForbidden()
        ->assertSee('Réservé aux administrateurs.');

    $this->get(route('admin.tags.index'))->assertForbidden();
});

it('forbids authors from creating and deleting tags', function () {
    $tag = Tag::factory()->create();
    $author = User::factory()->create();

    $this->actingAs($author)
        ->post(route('admin.tags.store'), ['name' => 'Interdit', 'slug' => 'interdit'])
        ->assertForbidden();

    $this->delete(route('admin.tags.destroy', $tag))->assertForbidden();
    $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    $this->assertDatabaseMissing('tags', ['slug' => 'interdit']);
});

it('shows admin navigation links to admins but not authors', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertSee(route('admin.categories.index'))
        ->assertSee(route('admin.tags.index'));

    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertDontSee(route('admin.categories.index'))
        ->assertDontSee(route('admin.tags.index'));
});

it('grants category and tag management to admins only', function () {
    expect(Gate::forUser(User::factory()->create())->allows('manage-categories'))->toBeFalse()
        ->and(Gate::forUser(User::factory()->create())->allows('manage-tags'))->toBeFalse()
        ->and(Gate::forUser(User::factory()->admin()->create())->allows('manage-categories'))->toBeTrue()
        ->and(Gate::forUser(User::factory()->admin()->create())->allows('manage-tags'))->toBeTrue();
});
