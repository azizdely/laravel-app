<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tags = Tag::withCount([
            'posts' => fn (Builder $query) => $query->published(),
        ])->orderBy('name')->get();

        return TagResource::collection($tags);
    }

    public function show(Tag $tag): TagResource
    {
        $tag->loadCount([
            'posts' => fn (Builder $query) => $query->published(),
        ]);

        return TagResource::make($tag);
    }
}
