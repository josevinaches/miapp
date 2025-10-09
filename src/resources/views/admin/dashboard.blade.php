<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👑 Panel de Administración
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <p class="text-lg">Bienvenido, <strong>{{ auth()->user()->name }}</strong> 🙌</p>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    

                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
