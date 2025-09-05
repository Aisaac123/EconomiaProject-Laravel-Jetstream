<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Sección detallada de explicación --}}
        <div class="relative bg-gradient-to-r from-primary-500 via-primary-800 to-primary-800 dark:from-primary-700 dark:via-primary-800 dark:to-primary-900 rounded-2xl p-8 mb-10 text-center shadow-xl overflow-hidden">
            <div class="absolute inset-0 bg-grid-white/10 bg-[size:40px_40px]"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6">
                    <x-heroicon-c-currency-dollar class="size-16 text-white dark:text-primary-300" aria-hidden="true" />
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Anualidad
                </h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    “Un dólar hoy vale más que un dólar mañana, pero los pagos periódicos bien planificados crean riqueza constante.” — Benjamin Graham
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="#calculadora">
                        <button class="bg-white text-primary-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg">
                            Comenzar a calcular
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 space-y-6">
            <div class="grid-cols-12 grid gap-6">
                <div class="lg:col-span-6 col-span-12">
                    <div class="flex flex-col lg:min-h-[19rem] justify-between">
                        {{-- Descripción --}}
                        <div>
                            <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Descripción</h3>
                            <p class="text-gray-700 dark:text-gray-300 pt-2">
                                Una <strong>anualidad</strong> es una serie de pagos periódicos iguales durante un tiempo determinado. Se usa frecuentemente para préstamos, inversiones y fondos de ahorro. Puede calcularse en función del valor presente, valor futuro, tasa de interés, número de pagos o pago periódico.
                            </p>
                        </div>
                        {{-- Tipos de anualidad --}}
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Tipos de anualidades</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
                                <li><strong>Ordinaria:</strong> Pagos al final de cada período.</li>
                                <li><strong>Anticipada:</strong> Pagos al inicio de cada período.</li>
                                <li><strong>Perpetua:</strong> Pagos infinitos.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 col-span-12">
                    {{-- Fórmulas --}}
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Fórmula principal</h3>
                        <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-500 dark:border-primary-400 rounded-lg p-4 space-y-2 text-gray-700 dark:text-gray-300 text-sm">
                            <p><strong>Valor presente de una anualidad ordinaria:</strong></p>
                            <p class="font-medium text-lg">VP = PMT × [(1 - (1 + r)^-n) / r]</p>

                            <p><strong>Valor futuro de una anualidad ordinaria:</strong></p>
                            <p class="font-medium text-lg">VF = PMT × [((1 + r)^n - 1) / r]</p>

                            <p><strong>Variables:</strong></p>
                            <ul class="list-disc list-inside">
                                <li><strong>PMT:</strong> Pago periódico</li>
                                <li><strong>VP:</strong> Valor presente de la anualidad</li>
                                <li><strong>VF:</strong> Valor futuro de la anualidad</li>
                                <li><strong>r:</strong> Tasa de interés por período (decimal, ej. 5% = 0.05)</li>
                                <li><strong>n:</strong> Número total de pagos</li>
                            </ul>

                            <h4 class="font-medium mt-2 text-primary-700 dark:text-primary-300">Despejes útiles</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>PMT:</strong> PMT = VP × [r / (1 - (1+r)^-n)]</li>
                                <li><strong>VP:</strong> VP = PMT × [(1 - (1+r)^-n) / r]</li>
                                <li><strong>VF:</strong> VF = PMT × [((1+r)^n - 1) / r]</li>
                                <li><strong>r:</strong> Se calcula mediante métodos iterativos o aproximaciones.</li>
                                <li><strong>n:</strong> n = log(VF * r / PMT + 1) / log(1 + r)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ejemplos --}}
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Ejemplos prácticos</h3>
                <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-500 dark:border-primary-400 rounded-lg p-4 space-y-2 text-gray-700 dark:text-gray-300 text-sm">
                    <p>📌 <strong>Ejemplo 1:</strong> Deseas recibir $1,000 al final de cada año durante 5 años a una tasa del 5% anual. Valor presente:</p>
                    <p>VP = 1000 × [(1 - (1 + 0.05)^-5) / 0.05] ≈ $4,329.48</p>

                    <p>📌 <strong>Ejemplo 2:</strong> Inviertes $500 cada año durante 10 años a una tasa del 4% anual. Valor futuro:</p>
                    <p>VF = 500 × [((1 + 0.04)^10 - 1) / 0.04] ≈ $6,024.83</p>

                    <p>📌 <strong>Ejemplo 3:</strong> Con un valor presente de $10,000 y 8 pagos anuales, calcula el pago periódico a una tasa del 6% anual.</p>
                    <p>PMT = 10000 × [0.06 / (1 - (1+0.06)^-8)] ≈ $1,685.06</p>
                </div>
            </div>

            <p class="text-gray-700 dark:text-gray-300 mt-2">
                💡 Consejo: Una mayor tasa de interés o más pagos incrementan significativamente el valor futuro de la anualidad.
            </p>
        </div>

        {{-- Formulario de cálculo --}}
        <section id="calculadora">
            <div class="grid grid-cols-12 gap-x-4 mt-4">
                {{-- Calculadora --}}
                <div class="space-y-6 col-span-9 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <x-heroicon-o-information-circle class="h-5 w-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" />
                        <div class="text-sm">
                            <p class="text-gray-900 dark:text-white font-medium">
                                ¿Cómo usar la calculadora?
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 mt-1">
                                Complete todos los campos conocidos y deje vacío <strong>exactamente uno</strong> que desee calcular.
                            </p>
                        </div>
                    </div>
                    <form wire:submit="calculate('anualidad')" class="space-y-6">
                        {{ $this->form }}

                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <x-filament::button
                                    wire:click="limpiar"
                                    color="gray"
                                    outlined
                                    icon="heroicon-o-arrow-path"
                                >
                                    Limpiar
                                </x-filament::button>

                                <x-filament::button
                                    type="submit"
                                    color="primary"
                                    class="text-white"
                                >
                                    <x-slot:icon>
                                        <x-heroicon-o-calculator class="size-5 text-white" />
                                    </x-slot:icon>
                                    Calcular
                                </x-filament::button>

                            </div>
                        </div>
                    </form>
                </div>

                {{-- Explicación de la fórmula --}}
                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 col-span-3">
                    <h3 class="font-medium dark:text-white mb-2 text-primary-700">
                        Fórmula de la Anualidad
                    </h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <p><strong>VP = PMT × [(1 - (1 + r)^-n) / r]</strong></p>
                        <p><strong>VF = PMT × [((1 + r)^n - 1) / r]</strong></p>
                        <p><strong>PMT:</strong> Pago periódico</p>
                        <p><strong>VP:</strong> Valor presente</p>
                        <p><strong>VF:</strong> Valor futuro</p>
                        <p><strong>r:</strong> Tasa por período (decimal)</p>
                        <p><strong>n:</strong> Número de pagos</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
