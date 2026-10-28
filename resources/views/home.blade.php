<x-layout>
    <x-slot:title>Accueil</x-slot:title>

    <section class="max-w-3xl">
        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-300">Atelier Framework Côté Serveur</p>
        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Des idées simples,<br class="hidden sm:block"> bien structurées.</h1>
        <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
            Bienvenue sur Mini-CMS, un petit espace pour explorer les routes Laravel, les contrôleurs et les vues Blade.
        </p>
        <a href="{{ route('posts.index') }}" class="mt-7 inline-flex rounded-lg bg-indigo-500 px-5 py-3 font-semibold text-white transition hover:bg-indigo-400">
            Parcourir les articles
        </a>
    </section>
</x-layout>
