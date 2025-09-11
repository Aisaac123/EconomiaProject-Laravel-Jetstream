{{-- resources/views/filament/pages/tasa-interes.blade.php --}}
<x-filament::page>
    <div class="space-y-6">

        {{-- Sección Hero --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl shadow-md p-10 text-center text-white">
            <div class="flex justify-center mb-4">
                <x-heroicon-o-academic-cap class="w-12 h-12 text-white" />
            </div>
            <h1 class="text-3xl font-extrabold mb-3">Tasa de Interés</h1>
            <p class="text-lg max-w-2xl mx-auto">
                "La tasa de interés es el precio del dinero en el tiempo."
            </p>
            <div class="mt-6">
                <x-filament::button tag="a" href="#tipos" size="lg" color="white" class="text-emerald-700 font-bold">
                    Comenzar a aprender
                </x-filament::button>
            </div>
        </div>

        {{-- Descripción --}}
        <x-filament::card>
            <div class="prose max-w-none text-gray-700">
                <h2 class="text-xl font-bold mb-3">¿Qué es la Tasa de Interés?</h2>
                <p class="leading-relaxed">
                    La <strong>tasa de interés</strong> es el porcentaje que se paga o se recibe por el uso del dinero en un
                    periodo de tiempo determinado. Representa el costo de pedir prestado o el beneficio de invertir.
                </p>
            </div>
        </x-filament::card>

        {{-- Tipos de Tasa --}}
        <div id="tipos" class="grid md:grid-cols-2 gap-6">
            <x-filament::card>
                <div class="prose max-w-none text-gray-700">
                    <h2 class="text-xl font-bold mb-3">Interés Simple</h2>
                    <p>
                        Se calcula únicamente sobre el capital inicial.
                        Su crecimiento es <em>lineal</em>.
                    </p>
                    <div class="mt-4">
                        <span class="inline-block bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-semibold">
                            Fórmula: I = P × r × t
                        </span>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="prose max-w-none text-gray-700">
                    <h2 class="text-xl font-bold mb-3">Interés Compuesto</h2>
                    <p>
                        Se calcula sobre el capital y los intereses acumulados.
                        Su crecimiento es <em>exponencial</em>.
                    </p>
                    <div class="mt-4">
                        <span class="inline-block bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-sm font-semibold">
                            Fórmula: A = P × (1 + r/n)<sup>n×t</sup>
                        </span>
                    </div>
                </div>
            </x-filament::card>
        </div>

    </div>
</x-filament::page>
