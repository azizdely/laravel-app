<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexPostRequest;
use App\Http\Resources\Api\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(IndexPostRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $posts = Post::published()
            ->with(['user', 'category', 'tags'])
            ->latest('published_at')
            ->paginate(
                (int) ($validated['per_page'] ?? 10),
                ['*'],
                'page',
                isset($validated['page']) ? (int) $validated['page'] : null,
            );

        return PostResource::collection($posts);
    }

    public function show(Post $post): PostResource
    {
        Gate::authorize('view', $post);

        $post->load(['user', 'category', 'tags']);

        return PostResource::make($post);
    }
}
