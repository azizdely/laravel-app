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
        <x-alert type="success" title="Sprint en cours">
            Les pages du projet sont maintenant composées avec des composants Blade réutilisables.
        </x-alert>
        <a href="{{ route('posts.index') }}" class="inline-flex rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
            Parcourir les articles
        </a>
    </div>
</x-layout>
