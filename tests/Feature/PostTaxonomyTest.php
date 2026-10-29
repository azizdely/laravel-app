<?php

use App\Models\Post;
use App\Models\Tag;

beforeEach(function () {
    $this->withoutVite();
});

it('lists only the newest published posts assigned to a tag', function () {
    $tag = Tag::factory()->create([
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);
    $otherTag = Tag::factory()->create();

    $postTitles = [
        'Article tag le plus recent',
        'Article tag recent',
        'Article tag moyen',
        'Article tag ancien',
        'Article tag sur la page suivante',
    ];

    foreach ($postTitles as $index => $title) {
        $post = Post::factory()->published()->create([
            'title' => $title,
            'slug' => 'article-tag-'.$index,
            'published_at' => now()->subDays($index),
        ]);
        $post->tags()->attach($tag);
    }

    $draft = Post::factory()->create([
        'title' => 'Brouillon du tag',
        'slug' => 'brouillon-du-tag',
    ]);
    $draft->tags()->attach($tag);

    $unrelatedPost = Post::factory()->published()->create([
        'title' => 'Article avec un autre tag',
        'slug' => 'article-autre-tag',
    ]);
    $unrelatedPost->tags()->attach($otherTag);

    $this->get(route('posts.tag', $tag->slug))
        ->assertOk()
        ->assertSee('Tag : Laravel')
        ->assertSeeInOrder(array_slice($postTitles, 0, 4))
        ->assertSee('href="'.route('posts.tag', $tag->slug).'"', false)
        ->assertDontSee('Brouillon du tag')
        ->assertDontSee('Article avec un autre tag')
        ->assertSee('page=2', false);

    $this->get(route('posts.tag', ['tag' => $tag->slug, 'page' => 2]))
        ->assertOk()
        ->assertSee($postTitles[4])
        ->assertDontSee($postTitles[0]);
});

it('returns 404 when the tag slug does not exist', function () {
    $this->get('/posts/tag/tag-inconnu')->assertNotFound();
});

it('shows published tag counts in popularity order and hides tags with only drafts', function () {
    $draftOnlyTag = Tag::factory()->create([
        'name' => 'Tag de brouillon',
        'slug' => 'tag-de-brouillon',
    ]);
    $popularTag = Tag::factory()->create([
        'name' => 'Populaire',
        'slug' => 'populaire',
    ]);
    $alphabeticalTag = Tag::factory()->create([
        'name' => 'Alpha',
        'slug' => 'alpha',
    ]);
    $lessPopularTag = Tag::factory()->create([
        'name' => 'Moins populaire',
        'slug' => 'moins-populaire',
    ]);

    $draft = Post::factory()->create(['title' => 'Article brouillon']);
    $draft->tags()->attach($draftOnlyTag);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Tags populaires')
        ->assertDontSee('Tag de brouillon');

    foreach ([$popularTag, $alphabeticalTag] as $tag) {
        $posts = Post::factory(2)->published()->create();
        $posts->each(fn (Post $post) => $post->tags()->attach($tag));
    }

    $singlePost = Post::factory()->published()->create();
    $singlePost->tags()->attach($lessPopularTag);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Tags populaires')
        ->assertSeeInOrder([
            'Alpha (2)',
            'Populaire (2)',
            'Moins populaire (1)',
        ])
        ->assertSee('href="'.route('posts.tag', $popularTag->slug).'"', false)
        ->assertDontSee('Tag de brouillon');
});
