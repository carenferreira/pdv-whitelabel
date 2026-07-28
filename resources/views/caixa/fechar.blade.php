@extends('layouts.app')

@section('title', 'Fechar Caixa')

@section('content')
    <div class="mb-6">
        <a href="{{ route('caixa.index') }}" class="text-sm text-slate-500 transition hover:text-slate-700">
            ← Voltar para o caixa
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <h2 class="mb-6 text-lg font-semibold text-slate-800">Fechar Caixa</h2>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Valor Inicial</p>
                <p class="mt-1 text-lg font-semibold text-slate-800">R$ {{ number_format($caixa->opening_value / 100, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Total de Entradas (dinheiro)</p>
                <p class="mt-1 text-lg font-semibold text-green-600">R$ {{ number_format($expectedValue / 100, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-medium text-slate-500">Total de Saídas</p>
                <p class="mt-1 text-lg font-semibold text-red-500">R$ {{ number_format(($caixa->opening_value - $expectedValue) / 100, 2, ',', '.') }}</p>
            </div>
        </div>

        <form action="{{ route('caixa.fechar') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="actual_value" class="mb-1 block text-sm font-medium text-slate-700">Valor Real na Gaveta</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">R$</span>
                    <input type="text" id="actual_value" name="actual_value"
                           data-currency
                           class="w-full rounded-lg border border-slate-300 py-2 pl-10 pr-3 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('actual_value') border-red-400 @enderror"
                           placeholder="0,00" autocomplete="off">
                </div>
                <p class="mt-1 text-xs text-slate-400">Valor que você contou fisicamente na gaveta do caixa.</p>
                @error('actual_value')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="observations" class="mb-1 block text-sm font-medium text-slate-700">
                    Observações <span class="text-slate-400">(obrigatório se houver diferença)</span>
                </label>
                <textarea id="observations" name="observations" rows="2"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]"
                          placeholder="Justificativa caso o valor real não bata com o esperado">{{ old('observations') }}</textarea>
                @error('observations')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-lg bg-red-500 px-5 py-2 text-sm font-medium text-white transition hover:bg-red-600"
                        onclick="return confirm('Fechar o caixa? Não será possível registrar novas movimentações.')">
                    Fechar Caixa
                </button>
                <a href="{{ route('caixa.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection