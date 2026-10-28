<header class="border-b border-gray-200 bg-white">
    <nav class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-4" aria-label="Navigation principale">
        <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900">Mini-CMS</a>

        <div class="flex flex-wrap items-center gap-1">
            <x-navigation-link route-name="home" label="Accueil" />
            <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Articles" />
            <x-navigation-link route-name="about" label="À propos" />
            <x-navigation-link route-name="time" label="Heure" />
        </div>
    </nav>
</header>