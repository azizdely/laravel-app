@props(['post'])

<article {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm']) }}>
    <div class="flex flex-wrap items-center gap-2">
        @if (! $post->is_published)
            <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                Brouillon
            </span>
        @endif
    </div>

    <h2 class="mt-4 text-xl font-semibold text-gray-900">
        <a href="{{ route('posts.show', $post) }}" class="hover:text-red-700">
            {{ $post->title }}
        </a>
    </h2>
    <p class="mt-2 flex-1 leading-7 text-gray-600">{{ \Illuminate\Support\Str::limit($post->body, 120) }}</p>
    <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4 text-sm">
        <span class="text-gray-500">{{ $post->created_at->format('d/m/Y') }}</span>
        @if ($post->published_at)
            <a href="{{ route('posts.archive', $post->published_at->year) }}" class="font-medium text-red-700 hover:underline">
                Archive {{ $post->published_at->year }}
            </a>
        @else
            <span class="text-gray-500">Non publié</span>
        @endif
    </div>
    <a href="{{ route('posts.show', $post) }}" class="mt-4 text-sm font-medium text-red-700 hover:underline">Lire l'article</a>
</article>