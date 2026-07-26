@extends('layouts.guest')

@section('title', 'Entrar')

@section('content')

    <h1 class="mb-6 text-lg font-semibold text-slate-800">
        Entrar
    </h1>

    {{-- Mensagem de sessão (ex.: link de reset de senha enviado) --}}
    @session('status')
        <div class="mb-4 rounded-lg bg-[#0F9D74]/10 px-3 py-2 text-sm font-medium text-[#0F9D74]">
            {{ $value }}
        </div>
    @endsession

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- E-mail --}}
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">
                E-mail
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]"
            >
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Senha --}}
        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">
                Senha
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-[#0F9D74] focus:outline-none focus:ring-1 focus:ring-[#0F9D74]"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lembrar-me + esqueci a senha --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-slate-300 text-[#0F9D74] focus:ring-[#0F9D74]"
                >
                Lembrar-me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-slate-500 hover:text-slate-700">
                    Esqueci a senha
                </a>
            @endif
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#0F9D74] px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#0d8a66]"
        >
            Entrar
        </button>
    </form>

@endsection