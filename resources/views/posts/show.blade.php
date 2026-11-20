<x-layout>
    <x-slot:title>{{ $post->title }} - Mini-CMS</x-slot:title>

    <article class="mx-auto max-w-3xl">
        <x-page-header :title="$post->title" />

        <p class="mt-4 text-sm text-gray-500">
            @if ($post->published_at)
                Publié le
                <a href="{{ route('posts.archive', $post->published_at->year) }}" class="underline">
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d/m/Y') }}</time>
                </a>
            @else
                Brouillon
            @endif
            - {{ $post->category?->name ?? 'Sans catégorie' }}
            - par {{ $post->user?->name ?? 'auteur inconnu' }}
        </p>

        @if ($post->tags->isNotEmpty())
            <ul class="mt-4 flex flex-wrap gap-2" aria-label="Tags de l'article">
                @foreach ($post->tags as $tag)
                    <li class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ $tag->name }}</li>
                @endforeach
            </ul>
        @endif

        @if ($post->excerpt)
            <p class="mt-6 text-lg text-gray-600">{{ $post->excerpt }}</p>
        @endif

        <p class="mt-8 whitespace-pre-line text-lg leading-8 text-gray-700">{{ $post->body }}</p>

        @can('update', $post)
            <div class="mt-8 flex flex-wrap items-start gap-4">
                <form method="POST" action="{{ route('posts.publication', $post) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">{{ $post->is_published ? 'Dépublier' : 'Publier' }}</button>
                </form>

                <a href="{{ route('posts.edit', $post) }}" class="rounded-md border border-indigo-600 px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">Modifier</a>

                @can('delete', $post)
                    <details>
                        <summary class="cursor-pointer rounded-md border border-red-600 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Supprimer</summary>

                        <form method="POST" action="{{ route('posts.destroy', $post) }}" class="mt-3 rounded-md border border-red-200 bg-red-50 p-4">
                            @csrf
                            @method('DELETE')

                            <p class="mb-3 text-sm text-red-900">Cette action est définitive : l'article sera effacé de la base.</p>
                            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Confirmer la suppression</button>
                        </form>
                    </details>
                @endcan
            </div>
        @endcan
    </article>

    @if ($related->isNotEmpty())
        <section class="mx-auto mt-12 max-w-3xl" aria-labelledby="related-posts-heading">
            <h2 id="related-posts-heading" class="text-2xl font-bold tracking-tight text-gray-900">Dans la même catégorie</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @foreach ($related as $relatedPost)
                    <x-post-card :post="$relatedPost" />
                @endforeach
            </div>
        </section>
    @endif

    <p class="mt-8">
        <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Retour aux articles" />
    </p>
</x-layout>
