@props([
    'caixaAberto' => true,
    'caixaValor' => null,
])

<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6">

    {{-- Esquerda: toggle mobile + título da página --}}
    <div class="flex items-center gap-3">
        <button
            @click="$store.layout.sidebarOpen = ! $store.layout.sidebarOpen"
            class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden"
            aria-label="Abrir menu"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>

        <div class="hidden sm:block">
            <h1 class="text-base font-semibold text-slate-800">
                {{ $title ?? 'Painel' }}
            </h1>
        </div>
    </div>

    {{-- Centro/Direita: status do caixa + ações + usuário --}}
    <div class="flex items-center gap-3">

        {{-- Status do caixa: elemento característico de um PDV --}}
        <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-slate-50 py-1.5 pl-2.5 pr-3 sm:flex">
            <span class="relative flex h-2 w-2">
                @if($caixaAberto)
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#0F9D74] opacity-75"></span>
                @endif
                <span @class([
                    'relative inline-flex h-2 w-2 rounded-full',
                    'bg-[#0F9D74]' => $caixaAberto,
                    'bg-slate-400' => ! $caixaAberto,
                ])></span>
            </span>
            <span class="text-xs font-medium text-slate-600">
                @if($caixaAberto)
                    Caixa aberto
                    @if($caixaValor)
                        · R$ {{ number_format($caixaValor, 2, ',', '.') }}
                    @endif
                @else
                    Caixa fechado
                @endif
            </span>
        </div>

        {{-- Slot para ações extras (notificações, etc.) --}}
        @isset($actions)
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endisset

        {{-- Menu do usuário --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = ! open"
                @click.outside="open = false"
                class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-slate-100"
            >
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold text-slate-600">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <span class="hidden text-sm font-medium text-slate-700 md:block">
                    {{ auth()->user()->name ?? 'Usuário' }}
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div
                x-cloak
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-2 w-52 origin-top-right rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg shadow-slate-200/50"
            >
                <div class="border-b border-slate-100 px-3 py-2">
                    <p class="truncate text-sm font-medium text-slate-800">{{ auth()->user()->name ?? 'Usuário' }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->email ?? '' }}</p>
                </div>

                <a href="{{ route('profile.edit') ?? '#' }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    Meu perfil
                </a>
                <a href="#" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    Configurações
                </a>

                <form method="POST" action="{{ route('logout') ?? '#' }}" class="border-t border-slate-100 pt-1">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>