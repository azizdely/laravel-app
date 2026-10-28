<x-layout>
    <x-slot:title>{{ $pageTitle }}</x-slot:title>

    <section class="mx-auto max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-300">Page {{ $pageNumber }}</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-white">{{ $pageTitle }}</h1>
        <p class="mt-5 text-lg leading-8 text-slate-300">
            Cette page d’exemple est servie par une action de contrôleur. Son numéro est transmis dans l’URL et validé par la contrainte numérique de la route.
        </p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-lg border border-white/15 px-5 py-3 font-semibold text-white transition hover:border-indigo-300 hover:text-indigo-200">
            Retour à l’accueil
        </a>
    </section>
</x-layout>
