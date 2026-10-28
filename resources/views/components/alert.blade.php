@props(['type' => 'info', 'title'])

@php
    $appearance = match ($type) {
        'info' => ['container' => 'border-blue-300 bg-blue-50 text-blue-950', 'title' => 'text-blue-800', 'role' => 'status'],
        'warning' => ['container' => 'border-amber-300 bg-amber-50 text-amber-950', 'title' => 'text-amber-800', 'role' => 'alert'],
        'success' => ['container' => 'border-green-300 bg-green-50 text-green-950', 'title' => 'text-green-800', 'role' => 'status'],
        default => throw new InvalidArgumentException("Unsupported alert type [{$type}]."),
    };
@endphp

<section
    {{ $attributes->merge(['class' => "rounded-lg border p-4 {$appearance['container']}"]) }}
    role="{{ $appearance['role'] }}"
>
    <h2 class="font-semibold {{ $appearance['title'] }}">{{ $title }}</h2>
    <div class="mt-1 text-sm leading-6">{{ $slot }}</div>
</section>