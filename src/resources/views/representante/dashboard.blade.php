<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧑‍💼 Área de Representante
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg space-y-6">
                <p class="text-lg">Hola, <strong>{{ $user->name }}</strong> 👋</p>

                <div class="grid gap-4 sm:grid-cols-2">
                    @if ($comparsa)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <h3 class="font-semibold text-gray-800">🎭 Tu comparsa</h3>
                            <p class="mt-1 text-gray-700 text-lg">{{ $comparsa->nombre }}</p>
                            @if($comparsa->bando)
                                <p class="text-gray-600">
                                    Bando: <span class="font-medium">
                                        {{ $comparsa->bando->tipo }} — {{ $comparsa->bando->nombre ?? '' }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
                            ⚠️ Aún no tienes una comparsa asignada. Contacta con el Administrador.
                        </div>
                    @endif
                </div>

                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('representante.festeros.index') }}" class="font-medium">Festeros</a>
                        <p class="text-sm text-gray-600 mt-1">Listado y gestión.</p>
                    </li>
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('representante.menores.create') }}" class="font-medium">Alta de menor</a>
                        <p class="text-sm text-gray-600 mt-1">Festero + tutor en una pantalla.</p>
                    </li>
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('expedientes.index') }}" class="font-medium">Expedientes</a>
                        <p class="text-sm text-gray-600 mt-1">Consulta y edición.</p>
                    </li>
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('profile.edit') }}" class="font-medium">Mi perfil</a>
                        <p class="text-sm text-gray-600 mt-1">Datos y contraseña.</p>
                    </li>
                </ul>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mt-2 inline-flex items-center rounded-md border px-3 py-2 text-sm hover:shadow">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
