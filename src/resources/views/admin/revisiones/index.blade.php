<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">🗂️ Revisiones de Representantes</h2>
  </x-slot>

  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white p-4 shadow sm:rounded-lg">
        <form method="GET" class="flex flex-wrap items-center gap-2">
          <label>Ejercicio</label>
          <input type="number" name="ejercicio" value="{{ $ejercicio }}" class="w-24 border rounded px-2 py-1">
          <label>Estado</label>
          <select name="estado" class="border rounded px-2 py-1">
            <option value="">Todos</option>
            @foreach(['pendiente','revisado','aprobado','rechazado'] as $op)
              <option value="{{ $op }}" @selected($estado===$op)>{{ ucfirst($op) }}</option>
            @endforeach
          </select>
          <button class="rounded-md border px-3 py-1.5 hover:shadow">Filtrar</button>
        </form>
      </div>

      <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y">
          <thead class="bg-gray-50">
            <tr class="text-left text-sm">
              <th class="px-3 py-2">Fecha</th>
              <th class="px-3 py-2">Comparsa</th>
              <th class="px-3 py-2">Representante</th>
              <th class="px-3 py-2">Ejercicio</th>
              <th class="px-3 py-2">PDF</th>
              <th class="px-3 py-2">Estado</th>
              <th class="px-3 py-2 w-80">Acción</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse($revisiones as $r)
              <tr class="text-sm">
                <td class="px-3 py-2">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-3 py-2">{{ $r->comparsa->nombre }}</td>
                <td class="px-3 py-2">{{ $r->representante->name }}</td>
                <td class="px-3 py-2">{{ $r->ejercicio }}</td>
                <td class="px-3 py-2">
                  <a class="text-indigo-600 underline" target="_blank"
                     href="{{ asset('storage/'.$r->archivo_pdf) }}">Ver PDF</a>
                </td>
                <td class="px-3 py-2">
                  <span class="inline-flex items-center rounded-md border px-2 py-0.5
                    @class([
                      'bg-amber-50 border-amber-200 text-amber-800' => $r->estado==='pendiente',
                      'bg-blue-50 border-blue-200 text-blue-800'   => $r->estado==='revisado',
                      'bg-green-50 border-green-200 text-green-800'=> $r->estado==='aprobado',
                      'bg-red-50 border-red-200 text-red-800'       => $r->estado==='rechazado',
                    ])">
                    {{ ucfirst($r->estado) }}
                  </span>
                  @if($r->admin && $r->revisado_at)
                    <div class="text-xs text-gray-500 mt-1">
                      Por {{ $r->admin->name }} — {{ $r->revisado_at->format('d/m/Y H:i') }}
                    </div>
                  @endif
                </td>
                <td class="px-3 py-2">
                  <form method="POST" action="{{ route('admin.revisiones.actualizar', $r) }}" class="flex flex-wrap items-start gap-2">
                    @csrf @method('PATCH')
                    <select name="estado" class="border rounded px-2 py-1">
                      @foreach(['revisado','aprobado','rechazado'] as $op)
                        <option value="{{ $op }}" @selected($r->estado===$op)>{{ ucfirst($op) }}</option>
                      @endforeach
                    </select>
                    <input name="admin_comentario" class="border rounded px-2 py-1 w-64"
                           placeholder="Comentario (opcional)" value="{{ old('admin_comentario',$r->admin_comentario) }}">
                    <button class="rounded-md border px-3 py-1.5 hover:shadow">Guardar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="px-3 py-6 text-center text-gray-600">Sin revisiones.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div>{{ $revisiones->links() }}</div>
    </div>
  </div>
</x-app-layout>
