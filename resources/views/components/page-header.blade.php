@props(['title', 'subtitle' => null])

<header {{ $attributes->merge(['class' => 'border-b border-gray-200 pb-5']) }}>
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $title }}</h1>
    @if (filled($subtitle))
        <p class="mt-3 max-w-2xl text-lg leading-7 text-gray-600">{{ $subtitle }}</p>
    @endif
</header>