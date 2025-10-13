{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'MiApp') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fuentes opcionales --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    @stack('head')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 text-white antialiased">
    {{-- halos decorativos globales (opcionales) --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 opacity-40">
        <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-pink-500 blur-3xl mix-blend-screen"></div>
        <div class="absolute -bottom-24 -right-24 h-[22rem] w-[22rem] rounded-full bg-indigo-500 blur-3xl mix-blend-screen"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        {{-- header condicionado --}}
        @php
            // Rutas donde NO queremos barra superior (para no duplicar UI con tus botones)
            $hideHeaderOn = ['welcome', 'login', 'password.request', 'password.email', 'password.reset', 'password.confirm'];
        @endphp

        @unless (request()->routeIs($hideHeaderOn))
            <header class="px-6 py-4">
                @include('partials.nav')

                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" class="h-8 w-8 rounded-md" alt="Logo">
                    <span class="font-bold">{{ config('app.name', 'MiApp') }}</span>
                </a>
            </header>
        @endunless

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="px-6 py-8 text-center text-white/70 text-sm">
            © {{ date('Y') }} {{ config('app.name', 'MiApp') }}
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
