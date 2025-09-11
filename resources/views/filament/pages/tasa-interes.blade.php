{{-- resources/views/filament/pages/tasa-interes.blade.php --}}
<x-filament::page>
    <div class="space-y-6">

        {{-- Hero Section --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl shadow-lg p-10 text-center text-white">
            <div class="flex justify-center mb-4">
                <x-heroicon-o-academic-cap class="w-14 h-14 text-white" />
            </div>
            <h1 class="text-4xl font-extrabold mb-3">Tasa de Interés</h1>
            <p class="text-lg max-w-2xl mx-auto opacity-90">
                "La tasa de interés es el precio del dinero en el tiempo."
            </p>
            <div class="mt-6">
                <x-filament::button tag="a" href="#tipos" size="lg" color="white" class="text-emerald-700 font-bold">
                    Comenzar a aprender
                </x-filament::button>
            </div>
        </div>

        {{-- Descripción --}}
        <x-filament::card class="bg-gray-900 text-gray-100">
            <h2 class="text-xl font-bold text-emerald-400 mb-3">¿Qué es la Tasa de Interés?</h2>
            <p class="leading-relaxed">
                La <strong class="text-white">tasa de interés</strong> es el porcentaje que se paga o se recibe por el uso del dinero en un
                periodo de tiempo determinado. Representa el costo de pedir prestado o el beneficio de invertir.
            </p>
        </x-filament::card>

        {{-- Tipos de Tasa --}}
        <div id="tipos" class="grid md:grid-cols-2 gap-6">
            <x-filament::card class="bg-gray-900 text-gray-100">
                <h2 class="text-xl font-bold text-emerald-400 mb-3">Interés Simple</h2>
                <p>
                    Se calcula únicamente sobre el capital inicial.
                    Su crecimiento es <em class="text-emerald-300">lineal</em>.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-emerald-800 text-emerald-200 px-3 py-1 rounded-full text-sm font-semibold">
                        Fórmula: I = P × r × t
                    </span>
                </div>
            </x-filament::card>

            <x-filament::card class="bg-gray-900 text-gray-100">
                <h2 class="text-xl font-bold text-emerald-400 mb-3">Interés Compuesto</h2>
                <p>
                    Se calcula sobre el capital y los intereses acumulados.
                    Su crecimiento es <em class="text-teal-300">exponencial</em>.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-teal-800 text-teal-200 px-3 py-1 rounded-full text-sm font-semibold">
                        Fórmula: A = P × (1 + r/n)<sup>n×t</sup>
                    </span>
                </div>
            </x-filament::card>
        </div>

    </div>
</x-filament::page>
