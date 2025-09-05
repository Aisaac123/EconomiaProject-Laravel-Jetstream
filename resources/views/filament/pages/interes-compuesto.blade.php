<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Sección detallada de explicación --}}

        <div class="relative bg-gradient-to-r from-primary-500 via-primary-800 to-primary-800 dark:from-primary-700 dark:via-primary-800 dark:to-primary-900 rounded-2xl p-8 mb-10 text-center shadow-xl overflow-hidden">
            <div class="absolute inset-0 bg-grid-white/10 bg-[size:40px_40px]"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6">
                    <x-heroicon-c-arrow-trending-up class="size-16 text-white dark:text-primary-300" aria-hidden="true" />
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Interés Compuesto
                </h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    "El interés compuesto es la fuerza más poderosa del universo." — Albert Einstein
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
                                El <strong>interés compuesto</strong> es un método financiero en el que los intereses generados se reinvierten, generando nuevos intereses sobre ellos. A diferencia del interés simple, donde los intereses se calculan sobre el capital inicial, aquí el capital crece exponencialmente con el tiempo.
                            </p>
                        </div>
                        {{-- Tipos de interés compuesto --}}
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Tipos de interés compuesto</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
                                <li><strong>Capitalización anual:</strong> Se calcula el interés una vez al año.</li>
                                <li><strong>Capitalización semestral:</strong> Dos veces al año.</li>
                                <li><strong>Capitalización trimestral o mensual:</strong> Mayor frecuencia genera un mayor crecimiento del capital.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 col-span-12">
                    {{-- Fórmulas --}}
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Fórmula principal</h3>
                        <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-500 dark:border-primary-400 rounded-lg p-4 space-y-2 text-gray-700 dark:text-gray-300 text-sm">
                            <p><strong>Fórmula del interés compuesto:</strong></p>
                            <p class="font-medium text-lg">A = P × (1 + r/n)^(n×t)</p>

                            <p><strong>Variables:</strong></p>
                            <ul class="list-disc list-inside">
                                <li><strong>A:</strong> Monto final después de intereses</li>
                                <li><strong>P:</strong> Capital inicial invertido</li>
                                <li><strong>r:</strong> Tasa de interés anual (decimal, ej. 5% = 0.05)</li>
                                <li><strong>n:</strong> Número de capitalizaciones por año</li>
                                <li><strong>t:</strong> Tiempo total de inversión en años</li>
                            </ul>

                            <h4 class="font-medium mt-2 text-primary-700 dark:text-primary-300">Despejes para otras variables</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>P (capital inicial):</strong> P = A / (1 + r/n)^(n×t)</li>
                                <li><strong>r (tasa de interés):</strong> r = n × ((A/P)^(1/(n×t)) - 1)</li>
                                <li><strong>n (frecuencia de capitalización):</strong> n = (ln(A/P) / ln(1 + r)) / t</li>
                                <li><strong>t (tiempo):</strong> t = ln(A/P) / (n × ln(1 + r/n))</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ejemplos --}}
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300">Ejemplos prácticos</h3>
                <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-500 dark:border-primary-400 rounded-lg p-4 space-y-2 text-gray-700 dark:text-gray-300 text-sm">
                    <p>📌 <strong>Ejemplo 1:</strong> Inviertes $1,000 a una tasa del 5% anual con capitalización anual durante 3 años.</p>
                    <p>A = 1000 × (1 + 0.05/1)^(1×3) = 1157.63</p>

                    <p>📌 <strong>Ejemplo 2:</strong> Mismo capital y tasa, capitalización semestral.</p>
                    <p>A = 1000 × (1 + 0.05/2)^(2×3) = 1159.69</p>

                    <p>📌 <strong>Ejemplo 3:</strong> Deseas $2,000 en 5 años a una tasa del 4% anual, capitalización mensual.</p>
                    <p>P = 2000 / (1 + 0.04/12)^(12×5) ≈ 1638.62</p>
                </div>
            </div>

            <p class="text-gray-700 dark:text-gray-300 mt-2">
                💡 Consejo: Mayor frecuencia de capitalización y mayor tiempo maximizan los beneficios del interés compuesto.
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
                    <form wire:submit="calculate('compuesto')" class="space-y-6">
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
                        Fórmula del Interés Compuesto
                    </h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <p><strong>A = P(1 + r/n)^(n×t)</strong></p>
                        <p><strong>A:</strong> Monto final</p>
                        <p><strong>P:</strong> Capital inicial</p>
                        <p><strong>r:</strong> Tasa de interés anual (decimal)</p>
                        <p><strong>n:</strong> Frecuencia de capitalización por año</p>
                        <p><strong>t:</strong> Tiempo en años</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
