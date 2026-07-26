@props(['href' => '#', 'active' => false, 'badge' => null])

@php
    $isActive = $active || request()->is(ltrim(parse_url($href, PHP_URL_PATH) ?? '', '/'));
@endphp

<a
    href="{{ $href }}"
    @class([
        'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
        'bg-[#0F9D74]/10 text-[#0F9D74]' => $isActive,
        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! $isActive,
    ])
>
    {{-- indicador de item ativo --}}
    <span
        @class([
            'h-5 w-1 shrink-0 rounded-full transition-colors',
            'bg-[#0F9D74]' => $isActive,
            'bg-transparent' => ! $isActive,
        ])
    ></span>

    {{-- ícone (passado via slot nomeado) --}}
    @isset($icon)
        <span @class([
            'shrink-0 [&>svg]:h-5 [&>svg]:w-5',
            'text-[#0F9D74]' => $isActive,
            'text-slate-400 group-hover:text-slate-600' => ! $isActive,
        ])>
            {{ $icon }}
        </span>
    @endisset

    <span class="flex-1 truncate">{{ $slot }}</span>

    @if($badge)
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
            {{ $badge }}
        </span>
    @endif
</a>