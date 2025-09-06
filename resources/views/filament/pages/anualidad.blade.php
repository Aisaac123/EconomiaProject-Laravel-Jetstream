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
                <x-heroicon-c-calendar-days class="size-16 text-white dark:text-primary-300" aria-hidden="true" />
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

                    {{-- Tipos --}}
                    <div class="pt-8">
                        <x-sections.contents.types title="Tipos de Anualidades">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Ordinaria:</strong> Pagos al final de cada período.</li>
                                <li><strong>Anticipada:</strong> Pagos al inicio de cada período.</li>
                                <li><strong>Perpetua:</strong> Pagos infinitos.</li>
                            </ul>
                        </x-sections.contents.types>
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
                    <p>📌 <strong>Ejemplo 1:</strong> Deseas recibir $1,000 al final de cada año durante 5 años a una tasa del 5% anual. Valor presente:</p>
                    <p>VP = 1000 × [(1 - (1 + 0.05)^-5) / 0.05] ≈ $4,329.48</p>

                    <p>📌 <strong>Ejemplo 2:</strong> Inviertes $500 cada año durante 10 años a una tasa del 4% anual. Valor futuro:</p>
                    <p>VF = 500 × [((1 + 0.04)^10 - 1) / 0.04] ≈ $6,024.83</p>

                    <p>📌 <strong>Ejemplo 3:</strong> Con un valor presente de $10,000 y 8 pagos anuales, calcula el pago periódico a una tasa del 6% anual.</p>
                    <p>PMT = 10000 × [0.06 / (1 - (1+0.06)^-8)] ≈ $1,685.06</p>
                <x-slot:advice>
                    Una mayor tasa de interés o más pagos incrementan significativamente el valor futuro de la anualidad.
                </x-slot:advice>
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
                <x-sections.contents.calculator-explanation title="Fórmulas de la Anualidad">
                    <p><strong>VP = PMT × [(1 - (1 + r)^-n) / r]</strong></p>
                    <p><strong>VF = PMT × [((1 + r)^n - 1) / r]</strong></p>
                    <p><strong>PMT:</strong> Pago periódico</p>
                    <p><strong>VP:</strong> Valor presente</p>
                    <p><strong>VF:</strong> Valor futuro</p>
                    <p><strong>r:</strong> Tasa por período (decimal)</p>
                    <p><strong>n:</strong> Número de pagos</p>
                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
