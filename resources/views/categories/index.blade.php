@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
    {{-- Cabeçalho --}}
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-slate-800">Categorias</h1>
        <a href="{{ route('categories.create') }}"
           class="rounded-lg bg-[#0F9D74] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#0C7D5C]">
            + Nova Categoria
        </a>
    </div>

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nome</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Descrição</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $category)
                    <tr class="transition hover:bg-slate-50">
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-800">
                            {{ $category->name }}
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500">
                            {{ $category->description ?? '—' }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if ($category->active)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">
                                    Inativo
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="text-sm font-medium text-slate-600 transition hover:text-slate-900">
                                Editar
                            </a>
                            <span class="mx-2 text-slate-300">|</span>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Desativar esta categoria?')"
                                        class="text-sm font-medium text-red-500 transition hover:text-red-700">
                                    Desativar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">
                            Nenhuma categoria cadastrada ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@endsection