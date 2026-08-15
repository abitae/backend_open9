@props([
    'label',
    'value',
    'hint' => null,
    'href' => null,
])

@php($tag = filled($href) ? 'a' : 'div')

<{{ $tag }}
    @if (filled($href)) href="{{ $href }}" wire:navigate @endif
    {{ $attributes->class([
        'group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm transition dark:border-zinc-700 dark:bg-zinc-900',
        'hover:border-accent/40 hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent' => filled($href),
    ]) }}
>
    <div class="text-xs font-medium tracking-wide text-zinc-500 uppercase">{{ $label }}</div>
    <div class="mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">{{ $value }}</div>
    @if (filled($hint))
        <div class="mt-1 text-xs text-zinc-400">{{ $hint }}</div>
    @endif
    {{ $slot }}
</{{ $tag }}>
