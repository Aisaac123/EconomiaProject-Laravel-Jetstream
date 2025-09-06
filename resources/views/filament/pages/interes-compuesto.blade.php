<x-filament-panels::page>
    <div class="space-y-6">
        <x-sections.heading-title
            title="Interés Compuesto"
            quote='"El interés compuesto es la fuerza más poderosa del universo." — Albert Einstein'
            button-text="Comenzar a calcular"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-arrow-trending-up class="size-16 text-white dark:text-primary-300" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>
        <x-sections.content>
            <div class="grid grid-cols-12 gap-4">
                <div class="lg:col-span-6 col-span-12">
                    {{-- Descripción --}}
                    <x-sections.contents.description>
                        <p>
                            El <strong>interés compuesto</strong> es un método financiero en el que los intereses generados se reinvierten,
                            generando nuevos intereses sobre ellos. A diferencia del interés simple, donde los intereses se calculan sobre el capital inicial,
                            aquí el capital crece exponencialmente con el tiempo.
                        </p>
                    </x-sections.contents.description>

                    {{-- Tipos --}}
                    <div class="pt-8">
                        <x-sections.contents.types title="Tipos de Interés Compuesto">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Capitalización anual:</strong> Se calcula el interés una vez al año.</li>
                                <li><strong>Capitalización semestral:</strong> Dos veces al año.</li>
                                <li><strong>Capitalización trimestral o mensual:</strong> Mayor frecuencia genera un mayor crecimiento del capital.</li>
                            </ul>
                        </x-sections.contents.types>
                    </div>
                </div>

                <div class="lg:col-span-6 col-span-12">
                    {{-- Fórmulas --}}
                    <x-sections.contents.formula>
                        <div class="bg-gray-50 dark:bg-gray-800 border-r-4 border-primary-700 dark:border-primary-400 rounded-lg p-4 space-y-2">
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
                    </x-sections.contents.formula>
                </div>
            </div>

            {{-- Ejemplos --}}
            <x-sections.contents.examples>
                    <p>📌 <strong>Ejemplo 1:</strong> Inviertes $1,000 a una tasa del 5% anual con capitalización anual durante 3 años.</p>
                    <p>A = 1000 × (1 + 0.05/1)^(1×3) = 1157.63</p>

                    <p>📌 <strong>Ejemplo 2:</strong> Mismo capital y tasa, capitalización semestral.</p>
                    <p>A = 1000 × (1 + 0.05/2)^(2×3) = 1159.69</p>

                    <p>📌 <strong>Ejemplo 3:</strong> Deseas $2,000 en 5 años a una tasa del 4% anual, capitalización mensual.</p>
                    <p>P = 2000 / (1 + 0.04/12)^(12×5) ≈ 1638.62</p>

                <x-slot:advice>
                    Mayor frecuencia de capitalización y mayor tiempo maximizan los beneficios del interés compuesto.
                </x-slot:advice>
            </x-sections.contents.examples>
        </x-sections.content>
        <x-sections.calculator>
            <x-slot:form>
                <x-sections.contents.calculator-form>
                    <x-forms.calculation-form calculation-type="compuesto" />
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <p><strong>A = P(1 + r/n)^(n×t)</strong></p>
                    <p><strong>A:</strong> Monto final</p>
                    <p><strong>P:</strong> Capital inicial</p>
                    <p><strong>r:</strong> Tasa de interés anual (decimal)</p>
                    <p><strong>n:</strong> Frecuencia de capitalización por año</p>
                    <p><strong>t:</strong> Tiempo en años</p>
                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
