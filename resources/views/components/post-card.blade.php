@props(['post'])

<article {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm']) }}>
    <div class="flex flex-wrap items-center gap-2">
        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
            {{ $post['year'] }}
        </span>
        @if ($post['featured'])
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                À la une
            </span>
        @endif
    </div>

    <h2 class="mt-4 text-xl font-semibold text-gray-900">
        <a href="{{ route('posts.show', ['slug' => $post['slug']]) }}" class="hover:text-red-700">
            {{ $post['title'] }}
        </a>
    </h2>
    <p class="mt-2 flex-1 leading-7 text-gray-600">{{ $post['summary'] }}</p>
    <div class="mt-5 flex items-center justify-between gap-3 border-t border-gray-100 pt-4 text-sm">
        <span class="text-gray-500">{{ $post['author'] }}</span>
        <a href="{{ route('posts.archive', ['year' => $post['year']]) }}" class="font-medium text-red-700 hover:underline">
            Articles de {{ $post['year'] }}
        </a>
    </div>
</article>