<x-layout>
    <x-slot:title>{{ $post['title'] }} - Mini-CMS</x-slot:title>

    <article class="mx-auto max-w-3xl">
        <x-page-header :title="$post['title']" :subtitle="$post['summary']" />

        <p class="mt-4 text-sm text-gray-500">
            Par {{ $post['author'] }} ·
            <a href="{{ route('posts.archive', $post['year']) }}" class="underline">
                <time datetime="{{ $post['published_at'] }}">{{ $post['published_at'] }}</time>
            </a>
        </p>

        <div class="mt-8 space-y-5 text-lg leading-8 text-gray-700">
            @foreach ($post['body'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>

    <p class="mt-8">
        <x-navigation-link route-name="posts.index" active-pattern="posts.*" label="Retour aux articles" />
    </p>
</x-layout>
