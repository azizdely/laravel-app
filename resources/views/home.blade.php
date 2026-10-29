<x-layout>
    <x-slot:title>Accueil - Mini-CMS</x-slot:title>

    <x-page-header
        title="Bienvenue sur Mini-CMS"
        subtitle="Le projet fil rouge de l'Atelier Framework Côté Serveur."
    />

    <div class="mt-6 max-w-2xl space-y-5">
        <p class="text-lg leading-8 text-gray-700">
            Explorez les routes Laravel, les contrôleurs et les vues Blade à travers des articles pédagogiques.
        </p>
        <a href="{{ route('posts.index') }}" class="inline-flex rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
            Parcourir les articles
        </a>
    </div>

    <section class="mt-10" aria-labelledby="latest-posts-heading">
        <h2 id="latest-posts-heading" class="text-2xl font-bold tracking-tight text-gray-900">Articles récents</h2>
        <div class="mt-5 grid gap-5 md:grid-cols-2">
            @forelse ($latestPosts as $post)
                <x-post-card :post="$post" />
            @empty
                <x-alert type="info" title="Aucun article récent" class="md:col-span-2">
                    Les articles publiés apparaîtront ici.
                </x-alert>
            @endforelse
        </div>
    </section>

    @if ($popularTags->isNotEmpty())
        <section class="mt-10" aria-labelledby="popular-tags-heading">
            <h2 id="popular-tags-heading" class="text-2xl font-bold tracking-tight text-gray-900">Tags populaires</h2>
            <ul class="mt-5 flex flex-wrap gap-3">
                @foreach ($popularTags as $tag)
                    <li>
                        <a href="{{ route('posts.tag', $tag->slug) }}" class="inline-flex rounded-full bg-indigo-50 px-3 py-1 font-medium text-indigo-700 hover:bg-indigo-100">
                            {{ $tag->name }} ({{ $tag->posts_count }})
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</x-layout>
