@props([
    'src' => asset('logo.svg'),
    'alt' => 'Logo Camping Herradura Mar y Sol',
])

<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'object-contain']) }}>
