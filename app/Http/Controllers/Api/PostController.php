<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\Api\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(IndexPostRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $posts = Post::published()
            ->with(['user', 'category', 'tags'])
            ->when($validated['category'] ?? null, fn (Builder $query, string $slug) => $query->whereRelation('category', 'slug', $slug))
            ->when($validated['tag'] ?? null, fn (Builder $query, string $slug) => $query->whereRelation('tags', 'slug', $slug))
            ->latest('published_at')
            ->paginate(
                (int) ($validated['per_page'] ?? 10),
                ['*'],
                'page',
                isset($validated['page']) ? (int) $validated['page'] : null,
            )
            ->withQueryString();

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        $post = $request->user()->posts()->create(Arr::except($validated, ['tags']));
        $post->tags()->sync($validated['tags'] ?? []);

        return PostResource::make($post->load('user', 'category', 'tags'))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Post $post): PostResource
    {
        Gate::authorize('view', $post);

        $post->load(['user', 'category', 'tags']);

        return PostResource::make($post);
    }

    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $validated = $request->validated();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published']
            ? ($post->published_at ?? now())
            : null;

        $post->update(Arr::except($validated, ['tags']));
        $post->tags()->sync($validated['tags'] ?? []);

        return PostResource::make($post->load('user', 'category', 'tags'));
    }

    public function destroy(Post $post): Response
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
