<x-filament-panels::page>
    <div class="space-y-6">
        <x-sections.heading-title
            title="Interés Simple"
            quote='"La simplicidad es la máxima sofisticación." — Leonardo da Vinci'
            button-text="Comenzar a calcular"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-banknotes class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>
        <x-sections.content>
            <div class="grid grid-cols-12 gap-4">
                <div class="lg:col-span-6 col-span-12">
                    {{-- Descripción --}}
                    <x-sections.contents.description>
                        <p>
                            El <strong>interés simple</strong> es un método de cálculo financiero donde los intereses se calculan únicamente
                            sobre el capital inicial. A diferencia del interés compuesto, los intereses generados no se reinvierten,
                            manteniendo un crecimiento lineal constante a lo largo del tiempo.
                        </p>
                    </x-sections.contents.description>

                    {{-- Tipos --}}
                    <div class="pt-8">
                        <x-sections.contents.types title="Características del Interés Simple">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Cálculo lineal:</strong> Los intereses se calculan solo sobre el capital inicial.</li>
                                <li><strong>Fácil de entender:</strong> Ideal para préstamos a corto plazo y cálculos rápidos.</li>
                                <li><strong>Predictible:</strong> El crecimiento es constante y fácil de proyectar en el tiempo.</li>
                            </ul>
                        </x-sections.contents.types>
                    </div>
                </div>

                <div class="lg:col-span-6 col-span-12">
                    {{-- Fórmulas --}}
                    <x-sections.contents.formula>
                        <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-700 dark:border-primary-400 rounded-lg p-4 space-y-2">
                            <p><strong>Fórmula del interés simple:</strong></p>
                            <p class="font-medium text-lg">A = P × (1 + r × t)</p>

                            <p><strong>Variables:</strong></p>
                            <ul class="list-disc list-inside">
                                <li><strong>A:</strong> Monto final después de intereses</li>
                                <li><strong>P:</strong> Capital inicial invertido</li>
                                <li><strong>r:</strong> Tasa de interés anual (decimal, ej. 5% = 0.05)</li>
                                <li><strong>t:</strong> Tiempo total de inversión en años</li>
                            </ul>

                            <p><strong>Fórmula del interés generado:</strong></p>
                            <p class="font-medium text-lg">I = P × r × t</p>

                            <h4 class="font-medium mt-2 text-primary-700 dark:text-primary-300">Despejes para otras variables</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>P (capital inicial):</strong> P = A / (1 + r × t)</li>
                                <li><strong>r (tasa de interés):</strong> r = (A / P - 1) / t</li>
                                <li><strong>t (tiempo):</strong> t = (A / P - 1) / r</li>
                            </ul>
                        </div>
                    </x-sections.contents.formula>
                </div>
            </div>

            {{-- Ejemplos --}}
            <x-sections.contents.examples>
                <div class="space-y-4">
                    <x-sections.contents.example
                        title="📌 Ejemplo 1: Inversión básica"
                        description="Inviertes $1,000 a una tasa del 5% anual durante 2 años."
                        solution="A = 1000 × (1 + 0.05 × 2) = 1000 × 1.10 = $1,100"
                    />

                    <x-sections.contents.example
                        title="📌 Ejemplo 2: Préstamo a corto plazo"
                        description="Préstamo de $5,000 al 12% anual por 6 meses (0.5 años)."
                        solution="A = 5000 × (1 + 0.12 × 0.5) = 5000 × 1.06 = $5,300"
                    />

                    <x-sections.contents.example
                        title="📌 Ejemplo 3: Cálculo de capital inicial"
                        description="Deseas obtener $2,200 en 1 año con una tasa del 10% anual."
                        solution="P = 2200 / (1 + 0.10 × 1) = 2200 / 1.10 ≈ $2,000"
                    />
                </div>

                <x-slot:advice>
                    El interés simple es ideal para cálculos rápidos y préstamos a corto plazo donde la simplicidad es prioritaria.
                </x-slot:advice>
            </x-sections.contents.examples>
        </x-sections.content>
        <x-sections.calculator>
            <x-slot:form>
                <x-sections.contents.calculator-form>
                    <x-forms.calculation-form calculation-type="simple" />
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <x-slot:formula_slot>
                        <p><strong>A = P × (1 + r × t)</strong></p>
                        <p><strong>I = P × r × t</strong></p>
                        <p><strong>P:</strong> P = A / (1 + r × t)</p>
                        <p><strong>r:</strong> r = (A / P - 1) / t</p>
                        <p><strong>t:</strong> t = (A / P - 1) / r</p>
                    </x-slot:formula_slot>
                    <x-slot:var_slot>
                        <p><strong>A:</strong> Monto final</p>
                        <p><strong>P:</strong> Capital inicial</p>
                        <p><strong>r:</strong> Tasa de interés anual (decimal)</p>
                        <p><strong>t:</strong> Tiempo en años</p>
                        <p><strong>I:</strong> Interés generado</p>
                    </x-slot:var_slot>

                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
