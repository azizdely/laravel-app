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
        </p>

        <p class="mt-8 whitespace-pre-line text-lg leading-8 text-gray-700">{{ $post->body }}</p>
    </article>

    <p class="mt-8">
        <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Retour aux articles" />
    </p>
</x-layout>
