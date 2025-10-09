<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">📄 Expedientes</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('ok'))
                <div class="mb-4 rounded bg-green-100 p-3">{{ session('ok') }}</div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('expedientes.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">➕
                    Nuevo</a>
                <span class="text-sm text-gray-500">Hola {{ auth()->user()->name }}?
                    {{ auth()->user()->hasRole('Admin') ? '👑 Hello!' : '🧑‍💼 Hello!' }}</span>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Título</th>
                            <th class="p-3 text-left">Estado</th>
                            <th class="p-3 text-left">Propietario</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expedientes as $e)
                            <tr class="border-t">

                                <td class="p-3">
                                    <a href="{{ route('expedientes.show', $e) }}" class="block hover:underline">
                                        <div class="font-medium" style="color:#0040f1">{{ $e->titulo }}</div>
                                    </a>
                                    <div class="mt-1" style="color:#15803d; font-size:16px; line-height:1.6"
                                        title="{{ $e->descripcion }}">
                                        {{ \Illuminate\Support\Str::limit($e->descripcion ?? '', 160) }}
                                    </div>
                                </td>


                                <td class="p-3">
                                    @php $emoji = ['abierto'=>'🟢','en_progreso'=>'🟡','cerrado'=>'🔴'][$e->estado] ?? '📦'; @endphp
                                    {{ $emoji }} {{ str_replace('_', ' ', $e->estado) }}
                                </td>
                                <td class="p-3">{{ $e->user->name }}</td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        @can('update', $e)
                                            <a href="{{ route('expedientes.edit', $e) }}"
                                                class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-1 text-blue hover:bg-amber-600">
                                                ✏️ Editar
                                            </a>
                                        @endcan

                                        @can('delete', $e)
                                            <form action="{{ route('expedientes.destroy', $e) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="inline-flex items-center rounded-lg bg-red-600 px-3 py-1 text-white hover:bg-red-700"
                                                    onclick="return confirm('¿Eliminar?')">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="p-4" colspan="4">No hay expedientes aún 💤</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-3">{{ $expedientes->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
