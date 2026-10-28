<x-layout>
    <x-slot:title>{{ $heading }} - Mini-CMS</x-slot:title>

    <x-page-header :title="$heading" />

    <div class="mt-6 grid gap-5 md:grid-cols-2">
        @forelse ($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <x-alert type="info" title="Aucun article">
                Aucun article n’a été publié pour cette année.
            </x-alert>
        @endforelse
    </div>
</x-layout>
