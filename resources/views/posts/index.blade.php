<x-layout>
    <x-slot:title>{{ $heading }} - Mini-CMS</x-slot:title>

    <x-page-header :title="$heading" />

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <p class="text-gray-600">{{ $posts->total() }} article(s)</p>
        <a href="{{ route('posts.create') }}" class="inline-flex rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
            Nouvel article
        </a>
    </div>

    <div class="mt-6 grid gap-5 md:grid-cols-2">
        @forelse ($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <x-alert type="warning" title="Aucun article" class="md:col-span-2">
                Aucun article n’a été trouvé pour cette sélection.
            </x-alert>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</x-layout>
