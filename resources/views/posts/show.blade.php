<x-layout>
    <x-slot:title>{{ $post->title }} - Mini-CMS</x-slot:title>

    @if (session('status'))
        <x-alert type="success" title="Enregistrement terminé" class="mb-6">
            {{ session('status') }}
        </x-alert>
    @endif

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

        <div class="mt-8 flex flex-wrap items-start gap-3">
            <a href="{{ route('posts.edit', $post) }}" class="inline-flex rounded-lg border border-red-600 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50">
                Modifier
            </a>

            <form action="{{ route('posts.publication', $post) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                    {{ $post->is_published ? 'Dépublier' : 'Publier' }}
                </button>
            </form>

            <details class="rounded-lg border border-red-200">
                <summary class="cursor-pointer px-4 py-2 text-sm font-medium text-red-700">Supprimer</summary>
                <div class="border-t border-red-200 p-4">
                    <p class="mb-3 text-sm text-gray-700">Cette action supprimera définitivement cet article.</p>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                            Confirmer la suppression
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </article>

    <p class="mt-8">
        <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Retour aux articles" />
    </p>
</x-layout>
