@props(['routeName', 'label', 'activePattern' => null])

@php
    $isActive = request()->routeIs($activePattern ?? $routeName);
@endphp

<a
    href="{{ route($routeName) }}"
    @class([
        'rounded-md px-3 py-2 text-sm font-medium transition',
        'bg-red-50 text-red-700' => $isActive,
        'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $isActive,
    ])
    @if ($isActive) aria-current="page" @endif
    {{ $attributes }}
>
    {{ $label }}
</a>