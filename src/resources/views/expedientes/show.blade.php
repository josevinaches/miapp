<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">📄 Expediente: {{ $expediente->titulo }}</h2>
    </x-slot>

    @php
        $emoji = ['abierto'=>'🟢','en_progreso'=>'🟡','cerrado'=>'🔴'][$expediente->estado] ?? '📦';
        $estado = str_replace('_',' ', $expediente->estado);
    @endphp

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
                <div><span class="text-sm text-gray-500">Estado</span>
                    <div class="text-base">{{ $emoji }} {{ $estado }}</div>
                </div>

                <div><span class="text-sm text-gray-500">Propietario</span>
                    <div class="text-base">{{ $expediente->user->name ?? '—' }}</div>
                </div>

                <div><span class="text-sm text-gray-500">Descripción</span>
                    <div class="mt-1 text-base text-gray-700 leading-relaxed">
                        {{ $expediente->descripcion ?? 'Sin descripción.' }}
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('expedientes.index') }}" class="rounded-lg px-3 py-1 bg-gray-200 hover:bg-gray-300">← Volver</a>

                    <div class="flex items-center gap-3">
                        @can('update', $expediente)
                            <a href="{{ route('expedientes.edit', $expediente) }}"
                               class="rounded-lg bg-amber-500 px-3 py-1 text-white hover:bg-amber-600">✏️ Editar</a>
                        @endcan

                        @can('delete', $expediente)
                            <form action="{{ route('expedientes.destroy', $expediente) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button class="rounded-lg bg-red-600 px-3 py-1 text-white hover:bg-red-700"
                                        onclick="return confirm('¿Eliminar?')">🗑️ Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
