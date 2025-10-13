<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💶 Cuota · {{ $festero->nombre }}
            @if(!empty($festero->dni))
                <span class="text-gray-500 font-normal">— DNI: {{ strtoupper($festero->dni) }}</span>
            @else
                <span class="text-gray-400 font-normal">— DNI: —</span>
            @endif
            ({{ $ejercicio }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 text-red-700 px-4 py-3">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tarjeta con info básica del festero --}}
            <div class="mb-4 bg-white p-4 shadow sm:rounded-lg text-sm text-gray-700">
                <div class="flex flex-wrap gap-4">
                    <div><span class="font-semibold">Nombre:</span> {{ $festero->nombre }}</div>
                    <div><span class="font-semibold">DNI:</span> {{ $festero->dni ? strtoupper($festero->dni) : '—' }}</div>
                    <div><span class="font-semibold">Email:</span> {{ $festero->email ?? '—' }}</div>
                    <div><span class="font-semibold">Teléfono:</span> {{ $festero->telefono ?? '—' }}</div>
                </div>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg space-y-4">
                <form method="POST" action="{{ route('representante.festeros.cuota.update', $festero) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Ejercicio</label>
                        <input type="number" name="ejercicio" value="{{ old('ejercicio',$ejercicio) }}"
                               class="w-full border rounded-md px-3 py-2" />
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Cuota asociación (€)</label>
                            <input type="number" step="0.01" min="0" name="cuota_asociacion"
                                   value="{{ old('cuota_asociacion',$cuota->cuota_asociacion) }}"
                                   class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Cuota fester (€)</label>
                            <input type="number" step="0.01" min="0" name="cuota_fester"
                                   value="{{ old('cuota_fester',$cuota->cuota_fester) }}"
                                   class="w-full border rounded-md px-3 py-2" />
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="pagada_asociacion" value="1"
                                   @checked(old('pagada_asociacion',$cuota->pagada_asociacion)) />
                            <span>Pagada asociación</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="pagada_fester" value="1"
                                   @checked(old('pagada_fester',$cuota->pagada_fester)) />
                            <span>Pagada fester</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                Total previsto: <strong>
                    € {{ number_format(($cuota->cuota_asociacion + $cuota->cuota_fester), 2, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>
</x-app-layout>
