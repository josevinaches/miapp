<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">➕ Nuevo expediente</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('expedientes.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Título</label>
                        <input name="titulo" class="mt-1 w-full rounded border p-2" required>
                        @error('titulo') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea name="descripcion" rows="4" class="mt-1 w-full rounded border p-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Estado</label>
                        <select name="estado" class="mt-1 w-full rounded border p-2">
                            <option value="abierto">🟢 Abierto</option>
                            <option value="en_progreso">🟡 En progreso</option>
                            <option value="cerrado">🔴 Cerrado</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button class="rounded bg-blue-600 text-white px-4 py-2">Guardar</button>
                        <a href="{{ route('expedientes.index') }}" class="rounded bg-gray-200 px-4 py-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
