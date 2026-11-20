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

    <section class="mt-10" aria-labelledby="category-list-heading">
        <h2 id="category-list-heading" class="text-2xl font-bold tracking-tight text-gray-900">Parcourir par catégorie</h2>
        <ul class="mt-5 flex flex-wrap gap-3" aria-label="Catégories">
            @foreach ($categories as $category)
                <li>
                    <a href="{{ route('posts.category', $category) }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 transition hover:border-red-600 hover:text-red-700">
                        {{ $category->name }} ({{ $category->posts_count }})
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    @if ($popularTags->isNotEmpty())
        <section class="mt-10" aria-labelledby="popular-tags-heading">
            <h2 id="popular-tags-heading" class="text-2xl font-bold tracking-tight text-gray-900">Tags populaires</h2>
            <ul class="mt-5 flex flex-wrap gap-3" aria-label="Tags populaires">
                @foreach ($popularTags as $tag)
                    <li>
                        <a href="{{ route('posts.tag', $tag) }}" class="inline-flex rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 transition hover:border-red-600 hover:text-red-700">
                            {{ $tag->name }} ({{ $tag->posts_count }})
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

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
</x-layout>
