@extends('layouts.app')

@section('title', 'Caixa')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-slate-800">Caixa</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    @if (!$caixaAberto)
        {{-- Nenhum caixa aberto — exibe formulário de abertura --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-800">Abrir Caixa</h2>

            <form action="{{ route('caixa.abrir') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="opening_value" class="mb-1 block text-sm font-medium text-slate-700">Valor Inicial</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">R$</span>
                        <input type="text" id="opening_value" name="opening_value"
                               data-currency
                               class="w-full rounded-lg border border-slate-300 py-2 pl-10 pr-3 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('opening_value') border-red-400 @enderror"
                               placeholder="0,00" autocomplete="off">
                    </div>
                    @error('opening_value')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="observations" class="mb-1 block text-sm font-medium text-slate-700">Observações <span class="text-slate-400">(opcional)</span></label>
                    <textarea id="observations" name="observations" rows="2"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]"
                              placeholder="Observação inicial do caixa">{{ old('observations') }}</textarea>
                </div>

                <button type="submit"
                        class="rounded-lg bg-[#0F9D74] px-5 py-2 text-sm font-medium text-white transition hover:bg-[#0C7D5C]">
                    Abrir Caixa
                </button>
            </form>
        </div>
    @else
        {{-- Caixa aberto — exibe resumo e ações --}}
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            Caixa aberto desde <strong>{{ $caixaAberto->opening_date->format('d/m/Y \à\s H:i') }}</strong>
            por <strong>{{ $caixaAberto->user->name }}</strong>
        </div>

        {{-- Cards de resumo --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Total de Entradas</p>
                <p class="mt-1 text-xl font-bold text-green-600">R$ {{ number_format($saldoEntradas / 100, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Total de Saídas</p>
                <p class="mt-1 text-xl font-bold text-red-500">R$ {{ number_format($saldoSaidas / 100, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Saldo Parcial</p>
                <p class="mt-1 text-xl font-bold @if($saldoParcial >= 0) text-slate-800 @else text-red-500 @endif">
                    R$ {{ number_format($saldoParcial / 100, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Ações --}}
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('caixa.sangria.form') }}"
               class="rounded-lg border border-orange-300 bg-white px-4 py-2 text-sm font-medium text-orange-600 transition hover:bg-orange-50">
                Sangria
            </a>
            <a href="{{ route('caixa.suprimento.form') }}"
               class="rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50">
                Suprimento
            </a>
            <a href="{{ route('caixa.fechar.form') }}"
               class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                Fechar Caixa
            </a>
        </div>

        {{-- Movimentações --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-800">Movimentações</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($movimentacoes as $mov)
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        {{-- Ícone conforme o tipo --}}
                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                            @if($mov->type->value === 'entry') bg-green-100 text-green-600
                            @else bg-red-100 text-red-500 @endif">
                            @if($mov->movement_type->value === 'opening')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                            @elseif($mov->movement_type->value === 'sangria' || $mov->movement_type->value === 'suprimento')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8-8 8-4-4-6 6"/></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </span>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $mov->movement_type?->label() ?? '—' }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $mov->created_at->format('d/m H:i') }}
                                @if($mov->payment_method)
                                    · {{ $mov->payment_method->label() }}
                                @endif
                            </p>
                            @if($mov->description)
                                <p class="mt-0.5 text-xs text-slate-500">{{ $mov->description }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="whitespace-nowrap text-sm font-semibold
                        @if($mov->type->value === 'entry') text-green-600
                        @else text-red-500 @endif">
                        {{ $mov->type->value === 'entry' ? '+' : '-' }} R$ {{ number_format($mov->value / 100, 2, ',', '.') }}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-sm text-slate-400">
                    Nenhuma movimentação registrada.
                </div>
            @endforelse
            </div>
        </div>
    @endif
@endsection