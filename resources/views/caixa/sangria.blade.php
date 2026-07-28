@extends('layouts.app')

@section('title', 'Sangria')

@section('content')
    <div class="mb-6">
        <a href="{{ route('caixa.index') }}" class="text-sm text-slate-500 transition hover:text-slate-700">
            ← Voltar para o caixa
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <h2 class="mb-6 text-lg font-semibold text-slate-800">Sangria</h2>

        <form action="{{ route('caixa.sangria') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="value" class="mb-1 block text-sm font-medium text-slate-700">Valor</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">R$</span>
                    <input type="text" id="value" name="value"
                           data-currency
                           class="w-full rounded-lg border border-slate-300 py-2 pl-10 pr-3 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('value') border-red-400 @enderror"
                           placeholder="0,00" autocomplete="off">
                </div>
                @error('value')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Descrição</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('description') border-red-400 @enderror"
                          placeholder="Ex: Depósito bancário">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-lg bg-orange-500 px-5 py-2 text-sm font-medium text-white transition hover:bg-orange-600">
                    Registrar Sangria
                </button>
                <a href="{{ route('caixa.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection