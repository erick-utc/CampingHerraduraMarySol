@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->class('block w-full px-2 py-1') }}>
        <div class="flex w-full flex-col items-center">
            <x-app-logo-icon class="h-20 w-full" />
            <span class="mt-1 block text-center text-xs font-semibold leading-tight text-zinc-700 dark:text-zinc-200">
                {{ __('Camping Herradura Mar y Sol') }}
            </span>
        </div>
    </a>
@else
    <flux:brand name="Camping Herradura Mar y Sol" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5" />
        </x-slot>
    </flux:brand>
@endif
