<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👶 Alta de festero menor + tutor
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('representante.menores.store') }}" class="space-y-6">
                    @csrf

                    {{-- Ejercicio (opcional; por defecto año actual) --}}
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Ejercicio</label>
                        <input type="number" name="ejercicio"
                               value="{{ old('ejercicio', $ejercicio ?? date('Y')) }}"
                               class="w-40 border rounded-md px-3 py-2">
                    </div>

                    <fieldset class="border rounded-md p-4">
                        <legend class="px-2 text-sm font-semibold text-gray-700">Datos del festero (menor)</legend>

                        <div class="mt-3 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}"
                                       class="w-full border rounded-md px-3 py-2" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">DNI/NIE (opcional)</label>
                                <input type="text" name="dni" value="{{ old('dni') }}"
                                       class="w-full border rounded-md px-3 py-2" placeholder="(si dispone)">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Email (opcional)</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full border rounded-md px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Teléfono (opcional)</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}"
                                       class="w-full border rounded-md px-3 py-2">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm text-gray-700 mb-1">Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                       class="w-56 border rounded-md px-3 py-2" required>
                                <p class="text-xs text-gray-500 mt-1">
                                    Debe ser menor a la fecha de corte (24/07 del ejercicio).
                                </p>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border rounded-md p-4">
                        <legend class="px-2 text-sm font-semibold text-gray-700">Datos del tutor (mayor de edad)</legend>

                        <div class="mt-3 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="tutor[nombre]" value="{{ old('tutor.nombre') }}"
                                       class="w-full border rounded-md px-3 py-2" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">DNI/NIE</label>
                                <input type="text" name="tutor[dni]" value="{{ old('tutor.dni') }}"
                                       class="w-full border rounded-md px-3 py-2" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Parentesco</label>
                                <input type="text" name="tutor[parentesco]" value="{{ old('tutor.parentesco') }}"
                                       class="w-full border rounded-md px-3 py-2" placeholder="Padre, madre, tutor legal…" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Teléfono (opcional)</label>
                                <input type="text" name="tutor[telefono]" value="{{ old('tutor.telefono') }}"
                                       class="w-full border rounded-md px-3 py-2">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm text-gray-700 mb-1">Email (opcional)</label>
                                <input type="email" name="tutor[email]" value="{{ old('tutor.email') }}"
                                       class="w-full border rounded-md px-3 py-2">
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('representante.festeros.index') }}"
                           class="px-4 py-2 rounded-md border bg-white hover:bg-gray-50">← Volver</a>

                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-md px-4 py-2">
                            Guardar menor + tutor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
