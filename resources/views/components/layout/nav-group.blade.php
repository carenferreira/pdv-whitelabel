@props(['label', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button
        @click="open = ! open"
        type="button"
        @class([
            'group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
            'text-[#0F9D74]' => $active,
            'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! $active,
        ])
    >
        <span class="h-5 w-1 shrink-0"></span>

        @isset($icon)
            <span @class([
                'shrink-0 [&>svg]:h-5 [&>svg]:w-5',
                'text-[#0F9D74]' => $active,
                'text-slate-400 group-hover:text-slate-600' => ! $active,
            ])>
                {{ $icon }}
            </span>
        @endisset

        <span class="flex-1 truncate text-left">{{ $label }}</span>

        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
            class="h-4 w-4 shrink-0 text-slate-400 transition-transform"
            :class="{ 'rotate-180': open }"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mt-1 space-y-1 pl-8"
    >
        {{ $slot }}
    </div>
</div>