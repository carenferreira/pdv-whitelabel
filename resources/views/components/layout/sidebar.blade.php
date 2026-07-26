@props(['company' => config('app.name', 'Minha Empresa')])

{{--
    Sidebar do PDV.
    Espera um Alpine.store('layout', { sidebarOpen: false }) registrado globalmente
    (ver resources/js/app.js) para controlar a abertura no mobile.
    Os itens de navegação usam <x-layout.nav-item> — ver componente abaixo.
--}}
<div
    x-cloak
    x-show="$store.layout.sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    @click.outside="if (window.innerWidth < 1024) $store.layout.sidebarOpen = false"
    class="fixed inset-y-0 left-0 z-40 flex h-full w-64 flex-col border-r border-slate-200 bg-white lg:translate-x-0"
>
    {{-- Logo / nome da empresa --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 px-5">
        @if(isset($logo))
            {{ $logo }}
        @else
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0F9D74] text-sm font-bold text-white">
                {{ strtoupper(substr($company, 0, 2)) }}
            </div>
        @endif
        <span class="truncate text-[15px] font-semibold text-slate-800">
            {{ $company }}
        </span>
    </div>

    {{-- Navegação --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {{ $slot }}
    </nav>

    {{-- Rodapé opcional da sidebar (ex.: versão do sistema, status de sync) --}}
    @isset($footer)
        <div class="border-t border-slate-200 px-5 py-3 text-xs text-slate-400">
            {{ $footer }}
        </div>
    @endisset
</div>

{{-- Overlay para mobile --}}
<div
    x-cloak
    x-show="$store.layout.sidebarOpen"
    x-transition.opacity
    @click="$store.layout.sidebarOpen = false"
    class="fixed inset-0 z-30 bg-slate-900/30 lg:hidden"
></div>