<nav class="w-full bg-white/80 dark:bg-gray-900/80 border-b">
  <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
    <a href="{{ route('welcome') }}" class="font-semibold">
      {{-- Tu logo / nombre --}}
      <span>🤝 MiApp</span>
    </a>

    <ul class="flex items-center gap-3 text-sm">
      {{-- Enlaces comunes --}}
      @auth
        <li>
          <a href="{{ route('dashboard') }}"
             class="@if (request()->routeIs('admin.dashboard','representante.dashboard')) text-indigo-600 font-semibold @endif">
            🏠 Panel
          </a>
        </li>

        <li>
          <a href="{{ route('expedientes.index') }}" class="@if (request()->routeIs('expedientes.*')) text-indigo-600 font-semibold @endif">
            📁 Expedientes
          </a>
        </li>

        {{-- Solo Admin --}}
        @role('Admin')
          <li>
            <a href="{{ route('admin.dashboard') }}" class="@if (request()->routeIs('admin.*')) text-indigo-600 font-semibold @endif">
              🛠️ Admin
            </a>
          </li>
          <li>
            <a href="{{ route('admin.comparsas.assign') }}" class="@if (request()->routeIs('admin.comparsas.*')) text-indigo-600 font-semibold @endif">
              🎭 Comparsas
            </a>
          </li>
        @endrole

        {{-- Solo Representante --}}
        @role('Representante')
          <li>
            <a href="{{ route('representante.dashboard') }}" class="@if (request()->routeIs('representante.*')) text-indigo-600 font-semibold @endif">
              🧑‍💼 Representante
            </a>
          </li>
        @endrole

        <li>
          <a href="{{ route('profile.edit') }}" class="@if (request()->routeIs('profile.*')) text-indigo-600 font-semibold @endif">
            👤 Perfil
          </a>
        </li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline">🚪 Salir</button>
          </form>
        </li>
      @endauth

      @guest
        <li>
          <a href="{{ route('login') }}" class="text-indigo-600 font-semibold">
            🔐 Entrar
          </a>
        </li>
      @endguest
    </ul>
  </div>
</nav>
