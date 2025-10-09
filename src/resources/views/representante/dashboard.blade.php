<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧑‍💼 Área de Representante
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <p class="text-lg">Hola, <strong>{{ auth()->user()->name }}</strong> 👋</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                   


                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <!-- Los campos de formulario aquí -->

    </form>

</x-app-layout>
