<x-layout>
    <x-slot:title>Catégorie introuvable - Mini-CMS</x-slot:title>

    <x-page-header title="Catégorie introuvable" subtitle="Cette catégorie n'existe pas ou a été supprimée." />

    <x-alert type="info" title="Catégories disponibles" class="max-w-2xl">
        Choisissez une catégorie pour parcourir ses articles.
    </x-alert>

    @if ($categories->isNotEmpty())
        <ul class="mt-6 flex flex-wrap gap-3">
            @foreach ($categories as $category)
                <li>
                    <a href="{{ route('posts.category', ['category' => $category->slug]) }}" class="font-medium text-indigo-700 hover:underline">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</x-layout>
