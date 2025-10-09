<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">🪧 Asignar comparsa a representante</h2></x-slot>
  <div class="py-6">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
      @if(session('ok')) <div class="mb-3 bg-green-100 p-3 rounded">✅ {{ session('ok') }}</div> @endif
      @if(session('error')) <div class="mb-3 bg-red-100 p-3 rounded">⚠️ {{ session('error') }}</div> @endif

      <div class="bg-white p-6 shadow sm:rounded-lg">
        <form method="POST" action="{{ route('admin.comparsas.assign.store') }}" class="space-y-4">
          @csrf
          <div>
            <label class="block text-sm font-medium">Representante</label>
            <select name="user_id" class="mt-1 w-full rounded border p-2" required>
              @foreach($representantes as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium">Comparsa (solo libres)</label>
            <select name="comparsa_id" class="mt-1 w-full rounded border p-2" required>
              @forelse($comparsas as $bandoNombre => $lista)
                <optgroup label="{{ $bandoNombre }}">
                  @foreach($lista as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                  @endforeach
                </optgroup>
              @empty
                <option value="">— No hay comparsas libres —</option>
              @endforelse
            </select>
          </div>

          <button class="rounded bg-blue-600 px-4 py-2 text-white">Asignar</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
