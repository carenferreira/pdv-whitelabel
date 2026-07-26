<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PDV') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 antialiased" x-data>

    <div class="w-full max-w-sm px-4">

        {{-- Logo / nome da empresa --}}
        <div class="mb-8 flex flex-col items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0F9D74] text-base font-bold text-white">
                {{ strtoupper(substr(config('app.name', 'PDV'), 0, 2)) }}
            </div>
            <span class="text-base font-semibold text-slate-800">
                {{ config('app.name', 'PDV') }}
            </span>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/50 sm:p-8">
            @yield('content')
        </div>

    </div>

</body>
</html>