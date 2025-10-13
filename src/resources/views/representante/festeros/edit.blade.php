<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      ✏️ Editar Festero
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
      <div class="bg-white p-6 shadow sm:rounded-lg">
        <form method="POST" action="{{ route('representante.festeros.update', $festero) }}" class="space-y-4">
          @csrf
          @method('PATCH')

          <div class="grid sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm">Nombre *</label>
              <input name="nombre" value="{{ old('nombre', $festero->nombre) }}" class="border rounded-md px-3 py-2 w-full" required>
              @error('nombre') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm">Primer apellido *</label>
              <input name="primer_apellido" value="{{ old('primer_apellido', $festero->primer_apellido) }}" class="border rounded-md px-3 py-2 w-full" required>
              @error('primer_apellido') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm">Segundo apellido</label>
              <input name="segundo_apellido" value="{{ old('segundo_apellido', $festero->segundo_apellido) }}" class="border rounded-md px-3 py-2 w-full">
              @error('segundo_apellido') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm">DNI</label>
              <input id="dni" name="dni" value="{{ old('dni', $festero->dni) }}" class="border rounded-md px-3 py-2 w-full" autocomplete="off">
              <p id="dniHelp" class="mt-1 text-sm"></p>
              @error('dni') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm">Email</label>
              <input type="email" name="email" value="{{ old('email', $festero->email) }}" class="border rounded-md px-3 py-2 w-full">
              @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm">Teléfono</label>
              <input name="telefono" value="{{ old('telefono', $festero->telefono) }}" class="border rounded-md px-3 py-2 w-full">
              @error('telefono') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm">Fecha nacimiento</label>
              <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($festero->fecha_nacimiento)->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 w-full">
              @error('fecha_nacimiento') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="flex items-center gap-6">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="trabuco" value="1" @checked(old('trabuco', $festero->trabuco))>
              <span>Trabuco</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="embarque" value="1" @checked(old('embarque', $festero->embarque))>
              <span>Embarque</span>
            </label>
          </div>
          @error('trabuco') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

          <div class="pt-2">
            <button class="bg-indigo-600 text-white rounded-md px-4 py-2">Actualizar</button>
            <a href="{{ route('representante.festeros.index') }}" class="ml-2 px-4 py-2 rounded-md border">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  (function() {
    const $dni = document.getElementById('dni');
    const $help = document.getElementById('dniHelp');
    if (!$dni || !$help) return;

    const url = "{{ route('representante.festeros.validate_dni') }}";
    const ignoreId = "{{ $festero->id }}";

    let t = null;
    const setMsg = (msg, cls) => {
      $help.textContent = msg;
      $help.className = 'mt-1 text-sm ' + (cls || 'text-gray-500');
    };

    $dni.addEventListener('input', () => {
      clearTimeout(t);
      const raw = $dni.value.trim();
      if (raw === '') { setMsg('', ''); return; }
      setMsg('Comprobando…', 'text-gray-500');
      t = setTimeout(async () => {
        try {
          const params = new URLSearchParams({ dni: raw, ignore_id: ignoreId });
          const res = await fetch(url + '?' + params.toString(), { headers: { 'Accept': 'application/json' } });
          const data = await res.json();
          if (data.valid) setMsg(data.message || 'DNI/NIE disponible.', 'text-green-600');
          else {
            const map = { format:'text-red-600', taken:'text-red-600', empty:'text-gray-500' };
            setMsg(data.message || 'No válido.', map[data.status] || 'text-red-600');
          }
        } catch(e) {
          setMsg('No se pudo validar ahora. Intenta de nuevo.', 'text-gray-500');
        }
      }, 350);
    });
  })();
  </script>
  @endpush
</x-app-layout>
