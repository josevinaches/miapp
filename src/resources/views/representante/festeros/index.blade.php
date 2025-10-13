<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">👥 Festeros</h2>
      <div class="flex items-center gap-2">
        <a href="{{ route('representante.festeros.export.pdf', ['ejercicio' => $ejercicio ?? now()->year]) }}"
           class="inline-flex items-center justify-center rounded-md border px-3 py-2 hover:shadow"
           title="Imprimir listado en PDF">
          🖨️ Imprimir PDF
        </a>
        <form method="POST" action="{{ route('representante.revisiones.enviar') }}" class="inline">
          @csrf
          <input type="hidden" name="ejercicio" value="{{ $ejercicio ?? now()->year }}">
          <button class="inline-flex items-center rounded-md border px-3 py-2 hover:shadow"
                  title="Enviar al Admin para revisión">
            📤 Enviar a revisión
          </button>
        </form>
      </div>
    </div>
  </x-slot>

  {{-- Estado de revisión del ejercicio --}}
  <div class="bg-white p-4 shadow sm:rounded-lg">
    @php
      $estado = isset($revision) ? ($revision->estado ?? null) : null;
      $badge = match ($estado) {
        'pendiente' => 'bg-amber-50 border-amber-200 text-amber-800',
        'revisado'  => 'bg-blue-50 border-blue-200 text-blue-800',
        'aprobado'  => 'bg-green-50 border-green-200 text-green-800',
        'rechazado' => 'bg-red-50 border-red-200 text-red-800',
        default     => 'bg-gray-50 border-gray-200 text-gray-700',
      };
    @endphp

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-center gap-2">
        <span class="text-sm">Revisión {{ $ejercicio }}:</span>
        @if ($estado)
          <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-sm {{ $badge }}">
            {{ ucfirst($estado) }}
          </span>
          @if (!empty($revision->admin_comentario))
            <span class="text-sm text-gray-600">· {{ $revision->admin_comentario }}</span>
          @endif
        @else
          <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-sm bg-gray-50 border-gray-200 text-gray-700">
            Sin enviar
          </span>
        @endif
      </div>

      <div class="flex items-center gap-2">
        @if (!empty($revision?->archivo_pdf))
          <a href="{{ asset('storage/' . $revision->archivo_pdf) }}" target="_blank"
             class="inline-flex items-center rounded-md border px-3 py-2 hover:shadow"
             title="Ver PDF enviado">
            📄 Ver PDF
          </a>
        @endif

        {{-- Reenviar / Enviar a revisión --}}
        <form method="POST" action="{{ route('representante.revisiones.enviar') }}" class="inline">
          @csrf
          <input type="hidden" name="ejercicio" value="{{ $ejercicio }}">
          <button class="inline-flex items-center rounded-md border px-3 py-2 hover:shadow"
                  title="{{ $estado ? 'Reenviar a revisión' : 'Enviar a revisión' }}">
            📤 {{ $estado ? 'Reenviar' : 'Enviar' }}
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

      {{-- Mensajes --}}
      @if (session('status'))
        <div class="rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
          {{ session('status') }}
        </div>
      @endif
      @if (session('ok'))
        <div class="rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
          {{ session('ok') }}
        </div>
      @endif
      @if (session('error'))
        <div class="rounded-md border border-red-200 bg-red-50 text-red-800 px-4 py-3">
          {{ session('error') }}
        </div>
      @endif

      {{-- Acciones superiores --}}
      <div class="bg-white p-4 shadow sm:rounded-lg">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

          {{-- Buscador --}}
          <form method="GET" action="{{ route('representante.festeros.index') }}" class="flex items-center gap-2">
            <input
              type="text"
              name="q"
              value="{{ $q ?? '' }}"
              placeholder="Buscar nombre, DNI, email, teléfono…"
              class="border rounded-md px-3 py-2 w-72">
            <button class="rounded-md border px-3 py-2 hover:shadow">Buscar</button>
            @if (!empty($q))
              <a href="{{ route('representante.festeros.index') }}" class="px-3 py-2 rounded-md border">Limpiar</a>
            @endif
          </form>

          {{-- Aplicar cuotas masivas --}}
          <form method="POST" action="{{ route('representante.festeros.cuota.apply') }}" class="flex flex-wrap items-center gap-2">
            @csrf
            <input type="number" name="ejercicio" value="{{ $ejercicio ?? now()->year }}"
                   class="w-24 border rounded px-2 py-1" title="Ejercicio">
            <input type="number" step="0.01" name="cuota_asociacion" placeholder="Asociación €"
                   class="w-36 border rounded px-2 py-1" required>
            <input type="number" step="0.01" name="cuota_fester" placeholder="Fester €"
                   class="w-28 border rounded px-2 py-1" required>
            <button class="rounded-md border px-3 py-2 hover:shadow" title="Aplicar a todos menos menores">
              💶 Aplicar cuotas
            </button>
          </form>

          {{-- Alta rápida --}}
          <a href="{{ route('representante.festeros.create') }}"
             class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white rounded-md px-4 py-2 font-medium">
            ➕ Nuevo
          </a>
        </div>
      </div>

      {{-- Tabla --}}
      <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y">
          <thead class="bg-gray-50">
            <tr class="text-left text-sm">
              <th class="px-3 py-2">Nombre</th>
              <th class="px-3 py-2">Apellidos</th>
              <th class="px-3 py-2">DNI</th>
              <th class="px-3 py-2">Email</th>
              <th class="px-3 py-2">Teléfono</th>
              <th class="px-3 py-2">Pago ({{ $ejercicio }})</th>
              <th class="px-3 py-2">Edad / Menor</th>
              <th class="px-3 py-2">Flags</th>
              <th class="px-3 py-2">Tutor/es</th>
              <th class="px-3 py-2 w-56 text-right">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse ($festeros as $f)
              <tr class="text-sm">
                <td class="px-3 py-2 font-medium">{{ $f->nombre }}</td>
                <td class="px-3 py-2">
                  {{ $f->primer_apellido ?? '—' }} {{ $f->segundo_apellido ?? '' }}
                </td>
                <td class="px-3 py-2">{{ $f->dni ?? '—' }}</td>
                <td class="px-3 py-2">{{ $f->email ?? '—' }}</td>
                <td class="px-3 py-2">{{ $f->telefono ?? '—' }}</td>

                {{-- Pago del ejercicio --}}
                @php
                  // Tomamos la única cuota por festero+ejercicio (por unique en BD)
                  $cq   = ($f->relationLoaded('cuotas') ? $f->cuotas->first() : null);
                  $asoc = (bool)($cq->pagada_asociacion ?? false);
                  $fest = (bool)($cq->pagada_fester ?? false);
                @endphp
                <td class="px-3 py-2">
                  <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border
                    {{ $asoc ? 'bg-green-50 border-green-200 text-green-700' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                    A: {{ $asoc ? 'Pagada' : 'Pendiente' }}
                  </span>
                  <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border ml-1
                    {{ $fest ? 'bg-green-50 border-green-200 text-green-700' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                    F: {{ $fest ? 'Pagada' : 'Pendiente' }}
                  </span>

                  @if (!$asoc || !$fest)
                    <a href="{{ route('representante.festeros.cuota.edit', $f) }}"
                       class="ml-2 inline-flex items-center rounded-md border px-2 py-0.5 text-xs bg-white hover:bg-gray-50"
                       title="Agregar pago / gestionar cuota">
                      💶 Gestionar
                    </a>
                  @endif
                </td>

                {{-- Edad / Menor --}}
                <td class="px-3 py-2">
                  @if ($f->fecha_nacimiento)
                    {{ $f->fecha_nacimiento->age }} años
                    @if (method_exists($f, 'isMenor') ? $f->isMenor() : false)
                      <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                        👶 Menor
                      </span>
                    @endif
                  @else
                    —
                  @endif
                </td>

                {{-- Flags rápidos --}}
                <td class="px-3 py-2">
                  @if ($f->trabuco)
                    <span title="Trabuco">🔫</span>
                  @endif
                  @if ($f->embarque)
                    <span title="Embarque">⛵</span>
                  @endif
                  @unless ($f->trabuco || $f->embarque)
                    <span class="text-gray-400">—</span>
                  @endunless
                </td>

                {{-- Tutores --}}
                <td class="px-3 py-2">
                  @if ($f->relationLoaded('tutores') && $f->tutores->count())
                    @foreach ($f->tutores as $t)
                      <span class="inline-block rounded-md border px-2 py-0.5 text-xs bg-white">
                        {{ $t->nombre }}
                      </span>
                    @endforeach
                  @elseif(property_exists($f, 'tutores_count'))
                    {{ $f->tutores_count }} tutor/es
                  @else
                    <span class="text-gray-400">—</span>
                  @endif
                </td>

                {{-- Acciones --}}
                <td class="px-3 py-2">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('representante.festeros.edit', $f) }}"
                       class="inline-flex items-center px-3 py-1 rounded-md border bg-white hover:bg-gray-50">
                      ✏️ Editar
                    </a>
                    <a href="{{ route('representante.festeros.cuota.edit', $f) }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-md border bg-white hover:bg-gray-50"
                       title="Gestionar cuota / pago">
                      💶 Cuota
                    </a>
                    <form method="POST" action="{{ route('representante.festeros.destroy', $f) }}"
                          class="inline"
                          onsubmit="return confirm('¿Eliminar a {{ $f->nombre }}?');">
                      @csrf
                      @method('DELETE')
                      <button class="px-3 py-1 rounded-md border border-red-300 text-red-700 bg-white hover:bg-red-50">
                        🗑️ Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="px-3 py-6 text-center text-sm text-gray-600">Sin resultados.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Paginación --}}
      <div>
        {{ $festeros->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
