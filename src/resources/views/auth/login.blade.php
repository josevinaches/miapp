{{--
  login.blade.php — Acceso coherente con la landing (welcome)
  - Fondo degradado, tarjeta compacta, logo, accesibilidad y dark mode
  - Botón para mostrar/ocultar contraseña (sin dependencias)
  - Enlaces a recuperar contraseña y registro (si existen)
--}}

<x-guest-layout>
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    {{-- halos decorativos --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-40">
      <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-pink-500 blur-3xl mix-blend-screen"></div>
      <div class="absolute -bottom-24 -right-24 h-[22rem] w-[22rem] rounded-full bg-indigo-500 blur-3xl mix-blend-screen"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6 sm:px-8 py-8">
      <div class="rounded-3xl bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-2xl p-6 sm:p-8">
        {{-- logo + título --}}
        <div class="flex flex-col items-center text-center">
          <img src="{{ asset('logo.png') }}" alt="Logotipo" class="h-12 w-12 rounded-md shadow mb-3">
          <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Iniciar sesión</h1>
          <p class="mt-1 text-white/70 text-sm">Accede a tu panel y gestiona tu comparsa</p>
        </div>

        {{-- estado de sesión --}}
        <x-auth-session-status class="mt-4" :status="session('status')" />

        {{-- errores globales --}}
        @if ($errors->any())
          <div class="mt-4 rounded-xl bg-red-500/10 text-red-200 ring-1 ring-red-400/30 p-3 text-sm">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- formulario --}}
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4" novalidate>
          @csrf

          {{-- Email --}}
          <div>
            <x-input-label for="email" :value="__('Correo')" class="text-white" />
            <x-text-input id="email" type="email" name="email" class="w-full mt-1" :value="old('email')" required autofocus autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
          </div>

          {{-- Password con toggle --}}
          <div>
            <div class="flex items-center justify-between">
              <x-input-label for="password" :value="__('Contraseña')" class="text-white" />
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-indigo-200 hover:text-white underline underline-offset-2">{{ __('¿Olvidaste tu contraseña?') }}</a>
              @endif
            </div>

            <div class="relative mt-1">
              <x-text-input id="password" type="password" name="password" class="w-full pr-12" required autocomplete="current-password" />
              <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 text-sm text-white/80 hover:text-white" aria-controls="password" aria-label="Mostrar u ocultar contraseña">👁️</button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
          </div>

          {{-- remember + submit --}}
          <div class="flex items-center justify-between text-sm text-white/80">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="remember" class="rounded border-white/30 bg-transparent text-indigo-400 focus:ring-indigo-300">
              <span>{{ __('Recuérdame') }}</span>
            </label>
          </div>

          <x-primary-button class="w-full !bg-white !text-indigo-900 hover:translate-y-[-1px] transition">{{ __('Entrar') }}</x-primary-button>
        </form>

        {{-- alternativos / registro --}}
        <div class="mt-6 text-center text-sm text-white/80">
          @if (Route::has('register'))
            <p>¿No tienes cuenta? <a href="{{ route('register') }}" class="font-semibold text-white underline underline-offset-4">Regístrate</a></p>
          @endif
          <p class="mt-2"><a href="{{ route('welcome') }}" class="text-white/80 hover:text-white underline underline-offset-4">← Volver a la portada</a></p>
        </div>

        <p class="mt-6 text-center text-[11px] text-white/60">© {{ date('Y') }} {{ config('app.name', 'MiApp') }}</p>
      </div>
    </div>

    {{-- script para alternar visibilidad de contraseña --}}
    <script>
      (function(){
        const btn = document.getElementById('togglePassword');
        const input = document.getElementById('password');
        if (btn && input) {
          btn.addEventListener('click', function(){
            const isPwd = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPwd ? 'text' : 'password');
            this.textContent = isPwd ? '🙈' : '👁️';
          });
        }
      })();
    </script>
  </section>
</x-guest-layout>
