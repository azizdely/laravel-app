<header class="border-b border-gray-200 bg-white">
    <nav class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-4" aria-label="Navigation principale">
        <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900">Mini-CMS</a>

        <div class="flex flex-wrap items-center gap-1">
            <x-navigation-link route-name="home" label="Accueil" />
            <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Articles" />
            <x-navigation-link route-name="about" label="À propos" />
            <x-navigation-link route-name="time" label="Heure" />
            @can('manage-categories')
                <x-navigation-link route-name="admin.categories.index" active-pattern="admin.categories.*" label="Catégories" />
            @endcan
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @auth
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>

                <a href="{{ route('my-posts') }}" class="text-sm font-medium text-gray-700 hover:underline">Mes articles</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Déconnexion</button>
                </form>
            @endauth

            @guest
                <x-navigation-link route-name="login" label="Connexion" />
                <x-navigation-link route-name="register" label="Inscription" />
            @endguest
        </div>
    </nav>
</header>