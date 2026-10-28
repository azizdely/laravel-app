<x-layout>
    <x-slot:title>Articles</x-slot:title>

    <section aria-labelledby="latest-posts">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-300">Le journal Mini-CMS</p>
                <h1 id="latest-posts" class="mt-1 text-3xl font-bold text-white">Tous les articles</h1>
            </div>
            <span class="text-sm text-slate-400">{{ count($posts) }} articles</span>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-white/[0.03] p-6 transition hover:border-indigo-400/50 hover:bg-white/[0.06]">
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <span class="rounded-full bg-indigo-400/10 px-3 py-1 font-semibold text-indigo-200">{{ $post['category'] }}</span>
                        <span class="text-slate-400">{{ $post['reading_time'] }}</span>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold leading-snug text-white">
                        <a href="{{ route('posts.show', ['slug' => $post['slug']]) }}" class="hover:text-indigo-200">
                            {{ $post['title'] }}
                        </a>
                    </h3>
                    <p class="mt-3 flex-1 leading-7 text-slate-300">{{ $post['excerpt'] }}</p>
                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 text-sm">
                        <time class="text-slate-400">{{ $post['published_at'] }}</time>
                        <a href="{{ route('posts.show', ['slug' => $post['slug']]) }}" class="font-semibold text-indigo-300 hover:text-indigo-200">
                            Lire l’article <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</x-layout>
