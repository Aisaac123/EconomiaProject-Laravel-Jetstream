{{-- filepath: resources/views/filament/pages/interes-simple.blade.php --}}
<x-filament::page>
    {{-- HERO / Introducción --}}
    <div class="rounded-2xl bg-gradient-to-r from-emerald-700 to-teal-700 p-12 text-center text-white mb-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-6xl mb-4">💡</div>
            <h1 class="text-4xl font-bold mb-2">Interés Simple</h1>
            <p class="text-lg/relaxed">
                El interés simple calcula los intereses sobre el capital inicial únicamente.
                Útil para préstamos o inversiones a corto plazo y para explicaciones rápidas.
            </p>
            <div class="mt-6">
                <a href="#interes-simple-form" class="inline-block rounded-lg bg-white text-teal-700 px-6 py-2 font-semibold shadow">
                    Comenzar a calcular
                </a>
            </div>
        </div>
    </div>

    {{-- Contenido: izquierda (descripción + ejemplos) / derecha (fórmula) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-4">
            <x-filament::card>
                <h3 class="text-lg font-bold mb-2">¿Qué es el interés simple?</h3>
                <p class="text-sm text-gray-400">
                    El interés simple se calcula sobre el capital inicial y no sobre los intereses acumulados.
                    Fórmula básica: <code>I = P × r × t</code>, y <code>A = P × (1 + r × t)</code>.
                </p>
            </x-filament::card>

            <x-filament::card>
                <h3 class="text-lg font-bold mb-2">Ejemplos guía</h3>
                <ul class="text-sm text-gray-400 space-y-2">
                    <li><strong>Ejemplo 1:</strong> Inviertes $1,000 al 5% anual por 2 años → I = 1000 × 0.05 × 2 = $100 → A = $1,100</li>
                    <li><strong>Ejemplo 2:</strong> Préstamo $5,000 al 12% anual por 0.5 años (6 meses) → I = 5000 × 0.12 × 0.5 = $300 → A = $5,300</li>
                    <li><strong>Ejemplo 3:</strong> Quieres $2,200 en 1 año con r = 10% → P = 2200 / (1 + 0.10×1) = $2,000</li>
                </ul>
            </x-filament::card>
        </div>

        <div>
            <x-filament::card>
                <h3 class="text-lg font-bold mb-2">Fórmula principal</h3>
                <div class="text-sm text-gray-400">
                    <p><strong>I = P × r × t</strong> — Interés generado</p>
                    <p class="mt-2"><strong>A = P × (1 + r × t)</strong> — Monto final</p>

                    <div class="mt-4">
                        <strong>Despejes útiles</strong>
                        <ul class="mt-2 text-sm space-y-1">
                            <li>P = A / (1 + r × t)</li>
                            <li>r = (A / P − 1) / t</li>
                            <li>t = (A / P − 1) / r</li>
                        </ul>
                    </div>
                </div>
            </x-filament::card>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <div id="interes-simple-form" class="mb-12">
        {{ $this->form }}
    </div>
</x-filament::page>
