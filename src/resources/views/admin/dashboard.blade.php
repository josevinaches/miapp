<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👑 Panel de Administración
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            {{-- Tarjetas rápidas --}}
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <p class="text-lg">
                    Bienvenido, <strong>{{ auth()->user()->name }}</strong> 🙌
                </p>

                <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('admin.revisiones.index') }}" class="font-medium">🗂️ Revisiones</a>
                        <p class="text-sm text-gray-600 mt-1">Listados enviados por representantes.</p>
                    </li>

                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('admin.comparsas.assign') }}" class="font-medium">
                            Asignar representantes ⇄ comparsas
                        </a>
                        <p class="text-sm text-gray-600 mt-1">Gestiona asignaciones.</p>
                    </li>
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('expedientes.index') }}" class="font-medium">
                            Expedientes
                        </a>
                        <p class="text-sm text-gray-600 mt-1">CRUD de expedientes.</p>
                    </li>
                    <li class="border rounded-md p-4 hover:shadow">
                        <a href="{{ route('profile.edit') }}" class="font-medium">
                            Mi perfil
                        </a>
                        <p class="text-sm text-gray-600 mt-1">Datos y contraseña.</p>
                    </li>
                </ul>
            </div>

            {{-- Filtro ejercicio --}}
            <div class="bg-white p-4 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <label class="text-sm">Ejercicio</label>
                    <input type="number" name="ejercicio" value="{{ $ejercicio ?? now()->year }}"
                        class="w-24 border rounded px-2 py-1">
                    <button class="rounded-md border px-3 py-1.5 hover:shadow">Filtrar</button>
                </form>
            </div>

            {{-- Tabla resumen por representante --}}
            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm">
                            <th class="px-3 py-2">Representante</th>
                            <th class="px-3 py-2">Comparsa</th>
                            <th class="px-3 py-2"># Festeros</th>
                            <th class="px-3 py-2">€ Adeudado ({{ $ejercicio ?? now()->year }})</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($stats ?? [] as $row)
                            @php
                                $ade = (float) ($row->adeudado ?? 0);
                            @endphp
                            <tr class="text-sm">
                                <td class="px-3 py-2 font-medium">{{ $row->representante }}</td>
                                <td class="px-3 py-2">{{ $row->comparsa }}</td>
                                <td class="px-3 py-2">{{ $row->total_festeros }}</td>
                                <td class="px-3 py-2">
                                    @if ($ade > 0)
                                        <span
                                            class="inline-flex items-center rounded-md border border-amber-200 bg-amber-50 text-amber-800 px-2 py-0.5">
                                            {{ number_format($ade, 2, ',', '.') }} €
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md border border-green-200 bg-green-50 text-green-800 px-2 py-0.5">
                                            Al día
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-gray-600">Sin datos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr class="text-sm font-semibold">
                            <td class="px-3 py-2" colspan="2">Totales</td>
                            <td class="px-3 py-2">{{ $totales['festeros'] ?? 0 }}</td>
                            <td class="px-3 py-2">
                                {{ $totales['adeudado'] ?? '0,00' }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
