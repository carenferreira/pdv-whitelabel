@extends('layouts.app')

@section('title', 'Novo Produto')

@section('content')
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm text-slate-500 transition hover:text-slate-700">
            ← Voltar para produtos
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <h2 class="mb-6 text-lg font-semibold text-slate-800">Novo Produto</h2>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nome --}}
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('name') border-red-400 @enderror"
                       placeholder="Ex: Coca-Cola 350ml">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoria --}}
            <div>
                <label for="category_id" class="mb-1 block text-sm font-medium text-slate-700">Categoria</label>
                <select id="category_id" name="category_id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('category_id') border-red-400 @enderror">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Descrição</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('description') border-red-400 @enderror"
                          placeholder="Descrição opcional do produto">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preço --}}
            <div>
                <label for="price_in_cents" class="mb-1 block text-sm font-medium text-slate-700">Preço (em centavos)</label>
                <input type="number" id="price_in_cents" name="price_in_cents" value="{{ old('price_in_cents') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 transition placeholder-slate-400 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74] @error('price_in_cents') border-red-400 @enderror"
                       placeholder="Ex: 2590">
                <p class="mt-1 text-xs text-slate-400">Valor em centavos. Ex: <strong>2590</strong> = R$ 25,90</p>
                @error('price_in_cents')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ativo --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" id="active" name="active" value="1" checked
                       class="h-4 w-4 rounded border-slate-300 text-[#0F9D74] focus:ring-[#0F9D74]">
                <label for="active" class="text-sm text-slate-700">Produto ativo</label>
            </div>

            {{-- Botões --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-lg bg-[#0F9D74] px-5 py-2 text-sm font-medium text-white transition hover:bg-[#0C7D5C]">
                    Salvar
                </button>
                <a href="{{ route('products.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection