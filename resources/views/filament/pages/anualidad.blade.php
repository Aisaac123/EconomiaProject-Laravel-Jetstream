<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Anualidad"
            quote="“Un dólar hoy vale más que un dólar mañana, pero los pagos periódicos bien planificados crean riqueza constante.” — Benjamin Graham"
            button-text="Comenzar a calcular"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-calendar-days class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Contenido estructurado --}}
        <x-sections.content>
            <div class="grid grid-cols-12 gap-4">
                <div class="lg:col-span-6 col-span-12">
                    {{-- Descripción --}}
                    <x-sections.contents.description>
                        <p>
                            Una <strong>anualidad</strong> es una serie de pagos periódicos iguales durante un tiempo determinado.
                            Se usa frecuentemente para préstamos, inversiones y fondos de ahorro. Puede calcularse en función del
                            valor presente, valor futuro, tasa de interés, número de pagos o pago periódico.
                        </p>
                    </x-sections.contents.description>

                    {{-- Tipos de Anualidades --}}
                    <div class="pt-8 space-y-3">
                        <x-sections.contents.types title="Tipos de Anualidades">

                            <x-filament::section collapsible collapsed heading="Anualidad Ordinaria (Vencida)">
                                <p class="text-gray-300">
                                    Pagos al final de cada período.
                                </p>
                                <p class="mt-2 font-mono text-green-400">
                                    VP = PMT × [(1 - (1 + r)^-n) / r] <br>
                                    VF = PMT × [((1 + r)^n - 1) / r]
                                </p>
                            </x-filament::section>

                            <x-filament::section collapsible collapsed heading="Anualidad Anticipada">
                                <p class="text-gray-300">
                                    Pagos al inicio de cada período.
                                </p>
                                <p class="mt-2 font-mono text-green-400">
                                    VP = PMT × [(1 - (1 + r)^-n) / r] × (1 + r)
                                </p>
                            </x-filament::section>

                            <x-filament::section collapsible collapsed heading="Anualidad Diferida">
                                <p class="text-gray-300">
                                    Incluye un período de gracia antes de iniciar los pagos.
                                </p>
                                <p class="mt-2 font-mono text-green-400">
                                    VP = [PMT × (1 - (1 + r)^-n) / r] ÷ (1 + r)^k
                                </p>
                            </x-filament::section>

                            <x-filament::section collapsible collapsed heading="Perpetuidad">
                                <p class="text-gray-300">
                                    Pagos infinitos, como fondos de becas o rentas perpetuas.
                                </p>
                                <p class="mt-2 font-mono text-green-400">
                                    VP = PMT / r
                                </p>
                            </x-filament::section>

                        </x-sections.contents.types>


                    </div>

                    <!-- Nota informativa -->
                        <div class="mt-4 bg-gray-100 dark:bg-gray-800 border-l-4 border-primary-500 p-4 rounded-lg shadow">
                            <p class="text-gray-700 dark:text-gray-300 font-medium">
                                ℹ️ <span class="text-primary-600 dark:text-primary-400 font-semibold">Nota:</span>
                                En esta aplicación se está utilizando la <strong>Anualidad Ordinaria (Vencida)</strong>.
                            </p>
                        </div>

                </div>

                <div class="lg:col-span-6 col-span-12">
                    {{-- Fórmulas --}}
                    <x-sections.contents.formula>
                        <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-700 dark:border-primary-400 rounded-lg p-4 space-y-2">
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
                    </x-sections.contents.formula>
                </div>
            </div>

            {{-- Ejemplos --}}
            <x-sections.contents.examples>
                <!-- Ejemplos prácticos -->
                <div class="mt-8">

                    <div class="space-y-6">
                        <!-- Ejemplo 1 -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow">
                            <p class="text-white dark:text-white font-semibold mb-2">
                                📌 Ejemplo 1 (Anualidad Vencida – Valor Presente)
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Deseas recibir $1,000 al final de cada año durante 5 años a una tasa del 5% anual.
                                <br>
                                <span class="block mt-2 font-mono text-green-600 dark:text-green-400 text-lg">
                                    VP = 1000 × [(1 - (1 + 0.05)^-5) / 0.05] ≈ $4,329.48
                                </span>
                            </p>
                        </div>

                        <!-- Ejemplo 2 -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow">
                            <p class="text-white dark:text-white font-semibold mb-2">
                                📌 Ejemplo 2 (Anualidad Vencida – Valor Futuro)
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Inviertes $500 cada año durante 10 años a una tasa del 4% anual.
                                <br>
                                <span class="block mt-2 font-mono text-green-600 dark:text-green-400 text-lg">
                                    VF = 500 × [((1 + 0.04)^10 - 1) / 0.04] ≈ $6,024.83
                                </span>
                            </p>
                        </div>

                        <!-- Ejemplo 3 -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow ">
                            <p class="text-white dark:text-white font-semibold mb-2">
                                📌 Ejemplo 3 (Anualidad Vencida – Pago Periódico)
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Con un valor presente de $10,000 y 8 pagos anuales, calcula el pago periódico a una tasa del 6% anual.
                                <br>
                                <span class="block mt-2 font-mono text-green-600 dark:text-green-400 text-lg">
                                    PMT = 10000 × [0.06 / (1 - (1+0.06)^-8)] ≈ $1,685.06
                                </span>
                            </p>
                        </div>

                        <!-- Ejemplo 4 -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow">
                            <p class="text-white dark:text-white font-semibold mb-2">
                                📌 Ejemplo 4 (Anualidad Anticipada – Valor Presente)
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Deseas pagar $2,000 al inicio de cada año durante 4 años, con una tasa del 5% anual.
                                <br>
                                <span class="block mt-2 font-mono text-green-600 dark:text-green-400 text-lg">
                                    VP = 2000 × [(1 - (1 + 0.05)^-4) / 0.05] × (1 + 0.05) ≈ $7,239.78
                                </span>
                            </p>
                        </div>

                        <!-- Ejemplo 5 -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow">
                            <p class="text-white dark:text-white font-semibold mb-2">
                                📌 Ejemplo 5 (Perpetuidad)
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Una empresa reparte $500 cada año de manera indefinida, con una tasa de descuento del 10% anual.
                                <br>
                                <span class="block mt-2 font-mono text-green-600 dark:text-green-400 text-lg">
                                    VP = 500 / 0.10 = $5,000
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Consejo -->
                    <div class="mt-8 bg-gray-200 dark:bg-gray-800 border-l-4 border-yellow-400 p-5 rounded-lg shadow">
                        <p class="text-yellow-700 dark:text-yellow-300 font-medium leading-relaxed">
                            💡 Consejo: Una mayor tasa de interés o más pagos incrementan significativamente el valor futuro de la anualidad.
                        </p>
                    </div>
                </div>


            </x-sections.contents.examples>
        </x-sections.content>

        {{-- Calculadora --}}
        <x-sections.calculator>
            <x-slot:form>
                <x-sections.contents.calculator-form>
                    <x-forms.calculation-form calculation-type="anualidad" />
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <x-slot:formula_slot>
                        <p><strong>VP = PMT × [(1 - (1 + r)^-n) / r]</strong></p>
                        <p><strong>VF = PMT × [((1 + r)^n - 1) / r]</strong></p>
                        <p><strong>PMT:</strong> VP × [r / (1 - (1+r)^-n)]</p>
                        <p><strong>VP:</strong> PMT × [(1 - (1+r)^-n) / r]</p>
                        <p><strong>VF:</strong> PMT × [((1+r)^n - 1) / r]</p>
                        <p><strong>n:</strong> log(VF * r / PMT + 1) / log(1 + r)</p>
                        <p><strong>r:</strong> Se calcula mediante métodos iterativos o aproximaciones.</p>
                    </x-slot:formula_slot>
                    <x-slot:var_slot>
                        <p><strong>PMT:</strong> Pago periódico</p>
                        <p><strong>VP:</strong> Valor presente</p>
                        <p><strong>VF:</strong> Valor futuro</p>
                        <p><strong>r:</strong> Tasa por período (decimal)</p>
                        <p><strong>n:</strong> Número de pagos</p>
                    </x-slot:var_slot>


                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
