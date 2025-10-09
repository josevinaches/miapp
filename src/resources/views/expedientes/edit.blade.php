<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">✏️ Editar expediente</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('expedientes.update',$expediente) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium">Título</label>
                        <input name="titulo" value="{{ old('titulo',$expediente->titulo) }}" class="mt-1 w-full rounded border p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea name="descripcion" rows="4" class="mt-1 w-full rounded border p-2">{{ old('descripcion',$expediente->descripcion) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Estado</label>
                        <select name="estado" class="mt-1 w-full rounded border p-2">
                            @foreach (['abierto'=>'🟢 Abierto','en_progreso'=>'🟡 En progreso','cerrado'=>'🔴 Cerrado'] as $val=>$label)
                                <option value="{{ $val }}" @selected(old('estado',$expediente->estado)===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button class="rounded bg-blue-600 text-white px-4 py-2">Actualizar</button>
                        <a href="{{ route('expedientes.index') }}" class="rounded bg-gray-200 px-4 py-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
