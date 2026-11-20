<x-layout>
    <x-slot:title>Article introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Article introuvable" subtitle="Cet article n'existe pas ou a été supprimé." />

    <x-alert type="info" title="Que faire ?" class="max-w-2xl">
        Vérifiez l'adresse, ou parcourez la liste des articles.
    </x-alert>

    <p class="mt-6">
        <a href="{{ route('posts.index') }}" class="font-medium text-red-700 hover:underline">Voir tous les articles</a>
    </p>

    @if ($recentPosts->isNotEmpty())
        <section class="mt-10" aria-labelledby="recent-posts-heading">
            <h2 id="recent-posts-heading" class="text-2xl font-bold tracking-tight text-gray-900">Articles récents</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                @foreach ($recentPosts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </section>
    @endif
</x-layout>
