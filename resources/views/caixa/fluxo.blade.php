@extends('layouts.app')

@section('title', 'Fluxo de Caixa')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Fluxo de Caixa</h1>
        <p class="mt-0.5 text-sm text-slate-500">Todas as movimentações registradas</p>
    </div>
    <a href="{{ route('caixa.index') }}"
       class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
        ← Voltar ao Caixa
    </a>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('caixa.fluxo') }}" class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        {{-- Tipo de movimentação --}}
        <div>
            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Tipo</label>
            <select name="movement_type"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 transition focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]">
                <option value="">Todos</option>
                <option value="opening" {{ request('movement_type') === 'opening' ? 'selected' : '' }}>Abertura</option>
                <option value="sale" {{ request('movement_type') === 'sale' ? 'selected' : '' }}>Venda</option>
                <option value="refund" {{ request('movement_type') === 'refund' ? 'selected' : '' }}>Estorno</option>
                <option value="expense" {{ request('movement_type') === 'expense' ? 'selected' : '' }}>Despesa</option>
                <option value="sangria" {{ request('movement_type') === 'sangria' ? 'selected' : '' }}>Sangria</option>
                <option value="suprimento" {{ request('movement_type') === 'suprimento' ? 'selected' : '' }}>Suprimento</option>
            </select>
        </div>

        {{-- Data inicial --}}
        <div>
            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Data inicial</label>
            <input type="date" name="date_start" value="{{ request('date_start') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 transition focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]">
        </div>

        {{-- Data final --}}
        <div>
            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Data final</label>
            <input type="date" name="date_end" value="{{ request('date_end') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 transition focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]">
        </div>

        {{-- Botões --}}
        <div class="flex items-end gap-2">
            <button type="submit"
                    class="rounded-lg bg-[#0F9D74] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#0C8260]">
                Filtrar
            </button>
            <a href="{{ route('caixa.fluxo') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                Limpar
            </a>
        </div>
    </div>
</form>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    @forelse ($movimentacoes as $mov)
        <div class="border-b border-slate-100 last:border-b-0">
            {{-- Linha principal — clicável --}}
            <button onclick="toggleMov({{ $mov->id }})"
                    class="flex w-full items-center justify-between px-4 py-3 text-left transition hover:bg-slate-50">
                <div class="flex items-center gap-3">
                    {{-- Ícone --}}
                    <span class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold
                        @if($mov->type->value === 'entry') bg-green-100 text-green-600
                        @else bg-red-100 text-red-500 @endif">

                        @if($mov->movement_type->value === 'opening')
                            {{-- Abertura: "+" --}}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                        @elseif(in_array($mov->movement_type->value, ['sangria', 'suprimento']))
                            {{-- Sangria / Suprimento: seta --}}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8-8 8-4-4-6 6"/></svg>
                        @elseif($mov->movement_type->value === 'sale')
                            {{-- Venda: cifrão --}}
                            <span class="text-sm font-bold">R$</span>
                        @elseif($mov->movement_type->value === 'expense')
                            {{-- Despesa: seta pra baixo --}}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        @else
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </span>

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-slate-800">
                                {{ $mov->payment_method?->label() ?? '—' }}
                            </span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                               {{ $mov->payment_method?->label() ?? '—' }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-400">
                            {{ $mov->created_at->format('d/m/Y H:i') }}
                            · Caixa #{{ $mov->cash_register_id }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="whitespace-nowrap text-sm font-semibold
                        @if($mov->type->value === 'entry') text-green-600
                        @else text-red-500 @endif">
                        {{ $mov->type->value === 'entry' ? '+' : '-' }}
                        R$ {{ number_format($mov->value / 100, 2, ',', '.') }}
                    </span>
                    {{-- Seta de expandir --}}
                    <svg id="arrow-{{ $mov->id }}"
                         class="h-4 w-4 text-slate-400 transition-transform duration-200"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            {{-- Detalhes expansíveis --}}
            <div id="details-{{ $mov->id }}"
                 class="hidden border-t border-slate-100 bg-slate-50 px-4 py-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Usuário</span>
                        <p class="mt-0.5 text-slate-700">{{ $mov->user?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Caixa</span>
                        <p class="mt-0.5 text-slate-700">#{{ $mov->cash_register_id }}</p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Descrição</span>
                        <p class="mt-0.5 text-slate-700">{{ $mov->description ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Origem</span>
                        <p class="mt-0.5 text-slate-700">{{ $mov->source_type ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">ID Origem</span>
                        <p class="mt-0.5 text-slate-700">{{ $mov->source_id ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="py-16 text-center text-sm text-slate-400">
            Nenhuma movimentação encontrada.
        </div>
    @endforelse
</div>

{{-- Paginação --}}
<div class="mt-6">
    {{ $movimentacoes->links() }}
</div>
@endsection

@push('scripts')
<script>
    function toggleMov(id) {
        const details = document.getElementById('details-' + id);
        const arrow = document.getElementById('arrow-' + id);

        details.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
</script>
@endpush