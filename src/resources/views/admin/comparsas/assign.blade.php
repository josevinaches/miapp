<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👑 Admin · Asignación de Representantes a Comparsas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            {{-- Alertas --}}
            @if (session('status'))
                <div class="rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-md border border-red-200 bg-red-50 text-red-700 px-4 py-3">
                    <ul class="list-disc ms-4">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario de asignación rápida --}}
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-semibold text-lg mb-4">➕ Nueva asignación</h3>
                <form method="POST" action="{{ route('admin.comparsas.assign.store') }}"
                    class="grid md:grid-cols-3 gap-3">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Representante</label>
                        <select name="user_id" required class="w-full border rounded-md px-3 py-2">
                            <option value="">— Selecciona —</option>
                            @foreach ($representantes as $r)
                                {{-- solo mostrar libres si el controlador ya filtra; si no, se puede marcar los ocupados como disabled --}}
                                <option value="{{ $r->id }}">{{ $r->name }} — {{ $r->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Comparsa</label>
                        <select name="comparsa_id" required class="w-full border rounded-md px-3 py-2">
                            <option value="">— Selecciona —</option>
                            @foreach ($comparsasSelect as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->nombre }} — {{ $c->festeros_count ?? $c->festeros()->count() }} festeros
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="flex items-end">
                        <button
                            class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white rounded-md px-4 py-2">
                            Asignar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabla de comparsas --}}
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h3 class="font-semibold text-lg">📋 Comparsas</h3>

                    {{-- Filtros simples en cliente --}}
                    <div class="flex gap-2">
                        <input id="filtroNombre" type="text" placeholder="Filtrar por nombre…"
                            class="border rounded-md px-3 py-2">
                        <select id="filtroBando" class="border rounded-md px-3 py-2">
                            <option value="">Todos los bandos</option>
                            @foreach ($bandos as $b)
                                <option value="{{ $b->tipo }}">{{ $b->tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tablaComparsas" class="min-w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left w-1/3">Comparsa</th>
                                <th class="px-3 py-2 text-left">Bando</th>
                                <th class="px-3 py-2 text-left">Representante</th>
                                <th class="px-3 py-2 text-right w-1/3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comparsas as $c)
                                <tr class="border-t" data-bando="{{ $c->bando->tipo ?? '' }}">
                                    <td class="px-3 py-2">
                                        @php
                                            $n = (int) ($c->festeros_count ?? $c->festeros()->count());
                                            $totalPrevisto = 0;
                                            $ej = $ejercicio ?? now()->year;
                                            $corte = \Carbon\Carbon::create((int) $ej, 7, 24);
                                            $hayMenores = false;

                                            // Suma € del ejercicio actual y detecta menores
                                            foreach ($c->festeros as $f) {
                                                // cuotas del año (si vienen precargadas, first(); si no, consulta directa)
                                                $cuota =
                                                    $f->cuotas->first() ??
                                                    $f->cuotas()->where('ejercicio', $ej)->first();
                                                if ($cuota) {
                                                    $totalPrevisto +=
                                                        (float) $cuota->cuota_asociacion + (float) $cuota->cuota_fester;
                                                }
                                                if (!empty($f->fecha_nacimiento)) {
                                                    $nac = \Carbon\Carbon::parse($f->fecha_nacimiento);
                                                    if ($nac->diffInYears($corte) < 18) {
                                                        $hayMenores = true;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="font-medium flex items-center gap-2">
                                            {{ $c->nombre }}
                                             <span class="text-xs text-gray-500">— {{ $c->festeros_count }} festeros</span>
                                            {{-- badge festeros --}}
                                            <span
                                                class="inline-flex items-center rounded-full text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-800">
                                                {{ $n }} festeros
                                            </span>

                                            {{-- badge € previsto --}}
                                            <span
                                                class="inline-flex items-center rounded-full text-xs font-semibold px-2 py-0.5 bg-emerald-100 text-emerald-800">
                                                € {{ number_format($totalPrevisto, 2, ',', '.') }}
                                            </span>

                                            {{-- botón Menores (visible si hay menores) --}}
                                            @if ($hayMenores)
                                                <a href="{{ route('admin.comparsas.menores', ['comparsa' => $c->id, 'ejercicio' => $ej]) }}"
                                                    class="ms-2 inline-flex items-center rounded-md text-xs font-semibold px-2 py-1 bg-amber-500/90 hover:bg-amber-600 text-white">
                                                    🧒 Menores
                                                </a>
                                            @endif
                                        </div>



                                        @if (!empty($c->bando))
                                            <div class="text-xs text-gray-500">#{{ $c->bando->id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @if (!empty($c->bando))
                                            <span class="inline-flex items-center gap-2">
                                                <span
                                                    class="rounded-full bg-gray-100 text-gray-700 text-xs px-2 py-0.5">
                                                    {{ $c->bando->tipo }}
                                                </span>
                                                <span class="text-gray-600 text-sm">{{ $c->bando->nombre }}</span>
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @if (!empty($c->representante))
                                            <div class="font-medium">{{ $c->representante->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $c->representante->email }}</div>
                                        @else
                                            <span class="text-gray-400">Sin representante</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            {{-- Ver festeros --}}
                                            <a href="{{ route('admin.comparsas.festeros', ['comparsa' => $c->id]) }}"
                                                class="px-3 py-1.5 rounded-md border bg-white hover:bg-gray-50">
                                                👥 Festeros
                                            </a>

                                            @if (!empty($c->representante))
                                                {{-- Desasignar --}}
                                                <form method="POST"
                                                    action="{{ route('admin.comparsas.unassign', ['comparsa' => $c->id]) }}"
                                                    onsubmit="return confirm('¿Quitar representante de {{ $c->nombre }}?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        class="px-3 py-1.5 rounded-md bg-pink-500/90 hover:bg-pink-600 text-white">
                                                        Desasignar
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Ir a Asignar con selección previa (opcional) --}}
                                                <a href="#"
                                                    onclick="document.querySelector('select[name=comparsa_id]').value='{{ $c->id }}'; window.scrollTo({top:0,behavior:'smooth'});"
                                                    class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">
                                                    Asignar ahora
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-t">
                                    <td class="px-3 py-4 text-gray-500" colspan="4">No hay comparsas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const $fNombre = document.getElementById('filtroNombre');
                const $fBando = document.getElementById('filtroBando');
                const $rows = Array.from(document.querySelectorAll('#tablaComparsas tbody tr'));

                function filtrar() {
                    const texto = ($fNombre.value || '').toLowerCase().trim();
                    const bando = ($fBando.value || '').toLowerCase().trim();

                    $rows.forEach(tr => {
                        const tNombre = tr.cells[0]?.innerText?.toLowerCase() || '';
                        const tBando = (tr.dataset.bando || '').toLowerCase();
                        const okNombre = !texto || tNombre.includes(texto);
                        const okBando = !bando || tBando === bando;
                        tr.style.display = (okNombre && okBando) ? '' : 'none';
                    });
                }
                $fNombre?.addEventListener('input', () => {
                    window.clearTimeout(window.__t);
                    window.__t = setTimeout(filtrar, 200);
                });
                $fBando?.addEventListener('change', filtrar);
            })();
        </script>
    @endpush
</x-app-layout>
