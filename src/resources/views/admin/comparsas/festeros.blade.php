<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👑 Admin · Festeros — {{ $comparsa->nombre }}
            <span class="text-sm text-gray-500">({{ optional($comparsa->bando)->tipo }} —
                {{ optional($comparsa->bando)->nombre }})</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="q" value="{{ $q ?? '' }}"
                            placeholder="Buscar nombre, DNI, email, teléfono" class="border rounded-md px-3 py-2 w-72">
                        <button class="bg-indigo-600 text-white rounded-md px-3 py-2">Buscar</button>
                        @if ($q !== '')
                            <a href="{{ route('admin.comparsas.festeros', ['comparsa' => $comparsa->id]) }}"
                                class="px-3 py-2 rounded-md border">Limpiar</a>
                        @endif
                    </form>

                    <a href="{{ route('admin.comparsas.assign') }}" class="px-3 py-2 rounded-md border">← Volver a
                        asignar</a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">Nombre</th>
                                <th class="px-3 py-2 text-left">DNI</th>
                                <th class="px-3 py-2 text-left">Email</th>
                                <th class="px-3 py-2 text-left">Teléfono</th>
                                <th class="px-3 py-2 text-left">Edad / Menor</th>
                                <th class="px-3 py-2 text-left">Tutor/es</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($festeros as $f)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $f->nombre }}</td>
                                    <td class="px-3 py-2">{{ $f->dni ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $f->email ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $f->telefono ?? '—' }}</td>

                                    <td class="px-3 py-2">
                                        @if ($f->fecha_nacimiento)
                                            {{ $f->fecha_nacimiento->age }} años
                                        @else
                                            —
                                        @endif

                                        @if ($f->esMenorPara($ejercicio))
                                            <span
                                                class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                👶 Menor {{ $ejercicio }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-2">
                                        @forelse($f->tutores as $t)
                                            <span class="inline-block rounded-md border px-2 py-0.5 text-xs bg-white">
                                                {{ $t->nombre }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400">—</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td class="px-3 py-4 text-gray-500" colspan="6">Sin festeros.</td>
                                </tr>
                            @endforelse
                        </tbody>


                    </table>
                </div>

                <div class="mt-4">{{ $festeros->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
