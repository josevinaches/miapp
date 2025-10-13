<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      👑 Admin · Menores · {{ $comparsa->nombre }} ({{ $ejercicio }})
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="bg-white p-6 shadow sm:rounded-lg">
        @if($menores->isEmpty())
          <p class="text-gray-600">No se han detectado menores.</p>
        @else
          <table class="min-w-full border">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left">Festero</th>
                <th class="px-3 py-2 text-left">Nacimiento</th>
                <th class="px-3 py-2 text-left">Tutores</th>
                <th class="px-3 py-2 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody>
            @foreach($menores as $f)
              <tr class="border-t">
                <td class="px-3 py-2">
                  <div class="font-medium">{{ $f->nombre }}</div>
                  <div class="text-xs text-gray-500">{{ $f->dni }}</div>
                </td>
                <td class="px-3 py-2">
                  {{ \Carbon\Carbon::parse($f->fecha_nacimiento)->format('d/m/Y') }}
                </td>
                <td class="px-3 py-2">
                  @forelse($f->tutores as $t)
                    <div class="text-sm">
                      {{ $t->nombre }} ({{ $t->parentesco ?? 'tutor' }}) — {{ $t->dni ?? 's/dni' }}
                    </div>
                  @empty
                    <span class="text-gray-500">Sin tutor</span>
                  @endforelse
                </td>
                <td class="px-3 py-2 text-right">
                  <a href="{{ route('admin.tutores.create', ['festero' => $f->id]) }}"
                     class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">
                      ➕ Añadir tutor
                  </a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
