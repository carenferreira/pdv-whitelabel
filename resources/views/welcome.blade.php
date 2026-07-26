@extends('layouts.guest')

@section('title', 'Bem-vindo')

@section('content')
    <div class="flex flex-col items-center gap-6 text-center">
        <h1 class="text-xl font-semibold text-slate-800">
            Bem-vindo
        </h1>

        <a
            href="{{ route('login') }}"
            class="w-full rounded-lg bg-[#0F9D74] px-4 py-2.5 text-center text-sm font-semibold text-white transition-colors hover:bg-[#0d8a66]"
        >
            Entrar
        </a>
    </div>
@endsection