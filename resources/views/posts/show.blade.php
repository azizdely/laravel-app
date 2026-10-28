<x-layout>
    <x-slot:title>{{ $post['title'] }}</x-slot:title>

    <article class="mx-auto max-w-3xl">
        <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-300 transition hover:text-indigo-200">
            <span aria-hidden="true">←</span> Tous les articles
        </a>

        <header class="mt-10 border-b border-white/10 pb-8">
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-400">
                <span class="rounded-full bg-indigo-400/10 px-3 py-1 font-semibold text-indigo-200">{{ $post['category'] }}</span>
                <time>{{ $post['published_at'] }}</time>
                <span>{{ $post['reading_time'] }} de lecture</span>
            </div>
            <h1 class="mt-6 text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $post['title'] }}</h1>
            <p class="mt-5 text-xl leading-8 text-slate-300">{{ $post['excerpt'] }}</p>
        </header>

        <div class="space-y-6 py-8 text-lg leading-8 text-slate-300">
            @foreach ($post['body'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>
</x-layout>
