@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-slate-800">Produtos</h1>
        <a href="{{ route('products.create') }}"
           class="rounded-lg bg-[#0F9D74] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#0C7D5C]">
            + Novo Produto
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Grid de cards responsivo --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($products as $product)
            <div x-data="{ expanded: false }"
                 class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                {{-- Placeholder para imagem futura --}}
                <div class="flex h-40 items-center justify-center bg-slate-100">
                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                    </svg>
                </div>

                {{-- Conteúdo do card --}}
                <div class="p-4">
                    {{-- Nome (esquerda) e Preço (direita) --}}
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-sm font-semibold text-slate-800">{{ $product->name }}</h3>
                        <span class="whitespace-nowrap text-sm font-bold text-slate-800">
                            R$ {{ number_format($product->price_in_cents / 100, 2, ',', '.') }}
                        </span>
                    </div>

                    {{-- Descrição resumida --}}
                    <p class="mt-1 text-xs text-slate-500">
                        {{ Str::limit($product->description ?? 'Sem descrição', 60) }}
                    </p>

                    {{-- Detalhes expandidos (Visualizar) --}}
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="mt-3 space-y-2 border-t border-slate-100 pt-3">
                        <div class="text-xs text-slate-600">
                            <span class="font-medium">Categoria:</span> {{ $product->category?->name ?? '—' }}
                        </div>
                        <div class="text-xs text-slate-600">
                            <span class="font-medium">Descrição completa:</span>
                            <p class="mt-0.5 text-slate-500">{{ $product->description ?? '—' }}</p>
                        </div>
                        <div class="text-xs">
                            <span class="font-medium text-slate-600">Status:</span>
                            @if($product->active)
                                <span class="font-medium text-green-600">Ativo</span>
                            @else
                                <span class="font-medium text-red-500">Inativo</span>
                            @endif
                        </div>
                        @if($product->external_id)
                            <div class="text-xs text-slate-500">
                                <span class="font-medium text-slate-600">ID externo:</span> {{ $product->external_id }}
                            </div>
                        @endif
                        <div class="text-xs text-slate-400">
                            Criado em {{ $product->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    {{-- Ações --}}
                    <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                        <a href="{{ route('products.edit', $product) }}"
                           class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-200">
                            Editar
                        </a>
                        <button @click="expanded = ! expanded"
                                class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-200">
                            <span x-text="expanded ? 'Recolher' : 'Visualizar'"></span>
                        </button>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="ml-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Desativar este produto?')"
                                    class="rounded-md px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">
                                Desativar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-sm text-slate-400">
                Nenhum produto cadastrado ainda.
            </div>
        @endforelse
    </div>

    {{-- Paginação --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection