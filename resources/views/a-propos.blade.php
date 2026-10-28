<x-layout>
    <x-slot:title>À propos - Mini-CMS</x-slot:title>

    <x-page-header title="À propos de Mini-CMS" />

    <div class="mt-6 space-y-3">
        <p class="text-gray-700">Mini-CMS est le projet fil rouge de l'Atelier Framework Côté Serveur (Laravel 13).</p>
        <p class="text-gray-700">Auteur : {{ $auteur }}</p>
        <p class="text-gray-700">Groupe : {{ $groupe }}</p>
    </div>

    <div class="mt-6 grid gap-4">
        <x-alert type="info" title="Projet pédagogique">
            Ce site accompagne l’apprentissage de Laravel et du développement web.
        </x-alert>
        <x-alert type="warning" title="Données provisoires">
            Les articles sont encore conservés dans le contrôleur à cette étape du projet.
        </x-alert>
    </div>
</x-layout>
