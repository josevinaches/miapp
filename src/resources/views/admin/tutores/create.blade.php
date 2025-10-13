<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">➕ Nuevo Tutor para {{ $festero->nombre }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.tutores.store', $festero) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-700">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}" class="w-full border rounded-md px-3 py-2" required>
                        @error('nombre') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">DNI/NIE</label>
                            <input name="dni" value="{{ old('dni') }}" class="w-full border rounded-md px-3 py-2" required>
                            @error('dni') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Parentesco</label>
                            <input name="parentesco" value="{{ old('parentesco') }}" class="w-full border rounded-md px-3 py-2" required>
                            @error('parentesco') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">Teléfono</label>
                            <input name="telefono" value="{{ old('telefono') }}" class="w-full border rounded-md px-3 py-2">
                            @error('telefono') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-md px-3 py-2">
                            @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.comparsas.menores', $festero->comparsa_id) }}" class="px-4 py-2 rounded-md border">Cancelar</a>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-md px-4 py-2">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
