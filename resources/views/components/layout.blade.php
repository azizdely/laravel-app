<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mini-CMS, un atelier de découverte de Laravel et Blade.">
    <title>{{ $title ?? 'Mini-CMS' }} · Mini-CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-950 via-slate-950 to-slate-950">
        <header class="border-b border-white/10">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5" aria-label="Navigation principale">
                <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-white">
                    <span class="text-indigo-400">mini</span>-CMS
                </a>
                <div class="flex items-center gap-6 text-sm text-slate-300">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Accueil</a>
                    <a href="{{ route('posts.index') }}" class="transition hover:text-white">Articles</a>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-12 sm:py-16">
            {{ $slot }}
        </main>

        <footer class="border-t border-white/10">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-6 py-6 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>Mini-CMS · Atelier Framework Côté Serveur</p>
                <a href="{{ route('version') }}" class="transition hover:text-white">Laravel {{ app()->version() }}</a>
            </div>
        </footer>
    </div>
</body>
</html>
