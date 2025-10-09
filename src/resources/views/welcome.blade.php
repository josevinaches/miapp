{{--
  welcome.blade.php — Landing página "a todo trapo" para Laravel 10+/11+
  - Diseño fullscreen, hero vistoso y tarjeta central no-alargada
  - Tailwind CSS (funciona con Vite) y alternativa CDN para pruebas rápidas
  - Incluye listados de comparsas por bandos (puedes pasar arrays desde el controlador)
--}}

<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Fiestas Moros y Cristianos') }}</title>

    {{-- Si usas Vite/Tailwind en Laravel (recomendado) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- *** Alternativa rápida sin build (quítala si usas Vite) ***
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              display: ['Poppins', 'ui-sans-serif', 'system-ui'],
              body: ['Inter', 'ui-sans-serif', 'system-ui']
            },
          }
        }
      }
    </script>
    --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Poppins:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
      /* Fallback por si no tienes el plugin aspect-ratio */
      .square { width: 100%; }
      @media (min-width: 768px){ .square { max-width: 980px; } }
      .square:before { content: ""; float: left; padding-top: 100%; } /* mantiene 1:1 */
      .square > .square-content { position: relative; clear: both; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 text-white font-body">
    {{-- Fondo decorativo --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 opacity-40">
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-pink-500 blur-3xl mix-blend-screen"></div>
        <div class="absolute -bottom-24 -right-24 h-[28rem] w-[28rem] rounded-full bg-indigo-500 blur-3xl mix-blend-screen"></div>
    </div>

    {{-- Header --}}
    <header class="relative z-10">
        <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Logotipo Asociación" class="h-10 w-10 rounded-md shadow-lg hidden sm:block">
                <span class="font-display text-xl font-black tracking-tight">{{ config('app.name', 'Moros & Cristianos') }}</span>
            </div>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="#bandos" class="hover:opacity-80">Bandos</a>
                <a href="#programa" class="hover:opacity-80">Programa</a>
                <a href="#contacto" class="hover:opacity-80">Contacto</a>
                @if (Route::has('login'))
                  @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-xl bg-white/10 px-4 py-2 backdrop-blur hover:bg-white/20 transition">Panel</a>
                  @else
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-white text-indigo-900 px-4 py-2 font-semibold hover:translate-y-[-1px] transition">Entrar</a>
                  @endauth
                @endif
            </nav>
        </div>
    </header>

    {{-- HERO fullscreen --}}
    <section class="relative z-10">
        <div class="mx-auto max-w-7xl px-6 py-10 md:py-16 lg:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.05]">
                        Fiestas de <span class="text-pink-300">Moros</span> & <span class="text-indigo-300">Cristianos</span>
                    </h1>
                    <p class="mt-4 text-white/80 text-lg max-w-prose">
                        Tradición, música y pólvora. Vive la entrada, los boatos y la banda sonora de nuestras comparsas.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#bandos" class="rounded-2xl bg-white text-indigo-900 px-5 py-3 font-semibold shadow hover:shadow-lg transition">Ver comparsas</a>
                        @if (Route::has('login'))
                          @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-2xl bg-white/10 px-5 py-3 font-semibold backdrop-blur hover:bg-white/20 transition">Ir al panel</a>
                          @else
                            <a href="{{ route('login') }}" class="rounded-2xl bg-pink-400/90 text-indigo-900 px-5 py-3 font-semibold shadow hover:shadow-lg transition">Entrar / Acceder</a>
                          @endauth
                        @endif
                    </div>
                </div>

                {{-- Tarjeta central cuadrada (no-alargada) --}}
                <div class="w-full">
                    <div class="square mx-auto">
                        <div class="square-content">
                            <div class="h-full w-full rounded-3xl bg-white/10 p-6 backdrop-blur-lg shadow-2xl ring-1 ring-white/20 flex flex-col">
                                <h2 class="font-display text-2xl font-bold">Cartel Oficial</h2>
                                <p class="text-white/70">Vista previa del cartel / carrusel de imágenes.</p>
                                <div class="mt-4 flex-1 rounded-2xl bg-black/30 grid place-items-center overflow-hidden">
                                    {{-- Sustituye por tu imagen o slider --}}
                                    <img src="{{ asset('logo.png') }}" alt="Marca" class="h-40 w-40 opacity-90">
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <a href="#inscripcion" class="rounded-xl bg-pink-400/90 text-indigo-900 px-4 py-2 text-center font-semibold hover:bg-pink-300 transition">Inscripción</a>
                                    <a href="#bases" class="rounded-xl bg-indigo-400/90 text-indigo-900 px-4 py-2 text-center font-semibold hover:bg-indigo-300 transition">Bases</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-center text-sm text-white/60">La tarjeta mantiene proporción 1:1 para evitar que se vea "alargada".</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Bandos / Listados --}}
    <section id="bandos" class="relative z-10 border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-16">
            <h2 class="font-display text-3xl md:text-4xl font-extrabold text-center">Bandos y Comparsas</h2>
            <p class="mt-2 text-center text-white/70">Selecciona tu bando y descubre las comparsas participantes.</p>

            @php
              // En producción, pásalo desde el controlador.
              $moro = [
                'Moros del Riff','Moros de Touareg','Pirates Berberiscos','Artillería del Islam',
                'Moros Mercaders','Moros Beduins','Moros de Capeta','Moros Pakkos','Artillería Mora','Guardia Negra','Negres'
              ];
              $cristiano = [
                'Pirates Corsaris','Contrabandistes','Pescadors','Artillería Cristiana','Caçadors',
                'Catalans','Llauradors','Marinos','Destralers','Voluntaris','Almogàvers'
              ];
            @endphp

            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <div class="rounded-3xl bg-white/10 p-6 backdrop-blur ring-1 ring-white/20 shadow-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-2xl font-bold">Bando Moro</h3>
                        <span class="rounded-full bg-pink-400/90 text-indigo-900 px-3 py-1 text-xs font-bold">{{ count($moro) }} comparsas</span>
                    </div>
                    <ul class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-white/90">
                        @foreach($moro as $c)
                          <li class="rounded-xl bg-white/5 px-3 py-2">{{ $c }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-3xl bg-white/10 p-6 backdrop-blur ring-1 ring-white/20 shadow-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-2xl font-bold">Bando Cristiano</h3>
                        <span class="rounded-full bg-indigo-400/90 text-indigo-900 px-3 py-1 text-xs font-bold">{{ count($cristiano) }} comparsas</span>
                    </div>
                    <ul class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-white/90">
                        @foreach($cristiano as $c)
                          <li class="rounded-xl bg-white/5 px-3 py-2">{{ $c }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Programa (placeholder) --}}
    <section id="programa" class="relative z-10 border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-16">
            <h2 class="font-display text-3xl md:text-4xl font-extrabold text-center">Programa</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @foreach([
                    ['title' => 'Entrada Mora', 'desc' => 'Desfile principal con boatos y marchas moras.'],
                    ['title' => 'Entrada Cristiana', 'desc' => 'Desfile cristiano con marchas épicas.'],
                    ['title' => 'Embajadas', 'desc' => 'Parlamentos tradicionales y toma del castillo.'],
                ] as $item)
                <div class="rounded-3xl bg-white/10 p-6 backdrop-blur ring-1 ring-white/20 shadow-lg">
                    <h3 class="font-display text-xl font-bold">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-white/80">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer id="contacto" class="relative z-10 border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-12 grid gap-6 md:grid-cols-2">
            <div>
                <h3 class="font-display text-xl font-bold">Contacto</h3>
                <p class="text-white/70">asociacion@example.com · Plaza Mayor, s/n</p>
            </div>
            <div class="md:text-right text-white/60 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'Moros & Cristianos') }} · Hecho con Laravel
            </div>
        </div>
    </footer>

    {{-- CTA fija en móvil para Login --}}
    @if (Route::has('login'))
      @guest
        <div class="fixed bottom-4 inset-x-4 z-50 md:hidden">
          <a href="{{ route('login') }}" class="block text-center rounded-2xl bg-white text-indigo-900 py-3 font-bold shadow-xl ring-1 ring-black/5">Entrar</a>
        </div>
      @endguest
    @endif
</body>
</html>
