{{-- resources/views/filament/pages/tasa-interes.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Hero Section --}}
        <x-sections.heading-title
            title="Tasa de Interés"
            quote="“La tasa de interés es el precio del dinero en el tiempo, reflejando el costo de financiarse o la recompensa de invertir.”"
            button-text="Explorar conceptos"
            href="#conceptos"
        >
            <x-slot:icon>
                <x-heroicon-c-academic-cap class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Contenido principal --}}
        <x-sections.content id="conceptos">

            {{-- Descripción --}}
            <x-sections.contents.description>
                <p>
                    La <strong>tasa de interés</strong> es el porcentaje que se paga o recibe por el uso del dinero en un periodo de tiempo.
                    Representa el <span class="text-primary-600 dark:text-primary-400 font-medium">costo de pedir prestado</span>
                    o el <span class="text-primary-600 dark:text-primary-400 font-medium">beneficio de invertir</span>.
                </p>
                <p class="mt-2">
                    Se utiliza en créditos, ahorros, inversiones, tarjetas de crédito y prácticamente en todas las operaciones financieras.
                    En otras palabras, la tasa de interés es la forma en que se mide cuánto crece o cuesta el dinero a lo largo del tiempo.
                </p>
            </x-sections.contents.description>

            {{-- Tipos de Tasa de Interés --}}
            <div class="pt-8">
                <x-sections.contents.types title="Tipos de Tasa de Interés">
                    <x-filament::section collapsible collapsed heading="Tasa Nominal">
                        <p class="text-gray-600 dark:text-gray-300">
                            Es la tasa expresada sin tener en cuenta la capitalización.
                            Por ejemplo, un 12% anual nominal puede capitalizarse mensual, trimestral, etc.
                        </p>
                        <p class="mt-2 font-mono text-green-500">
                            i<sub>n</sub> = r / m
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">
                            Donde <strong>r</strong> es la tasa anual y <strong>m</strong> es el número de periodos en que se divide el año.
                        </p>
                    </x-filament::section>

                    <x-filament::section collapsible collapsed heading="Tasa Efectiva">
                        <p class="text-gray-600 dark:text-gray-300">
                            Refleja el costo real de un préstamo o la ganancia real de una inversión,
                            considerando la capitalización de los intereses.
                        </p>
                        <p class="mt-2 font-mono text-green-500">
                            i<sub>ef</sub> = (1 + r/m)<sup>m</sup> - 1
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">
                            Esta es la tasa que se debe usar para comparar alternativas, ya que muestra
                            el verdadero crecimiento del dinero en el tiempo.
                        </p>
                    </x-filament::section>

                    <x-filament::section collapsible collapsed heading="Tasa Real">
                        <p class="text-gray-600 dark:text-gray-300">
                            Ajusta la tasa de interés considerando la inflación.
                            Indica el crecimiento real del poder adquisitivo.
                        </p>
                        <p class="mt-2 font-mono text-green-500">
                            i<sub>real</sub> = (1 + i<sub>nominal</sub>) / (1 + inflación) - 1
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">
                            Si la inflación es mayor que la tasa nominal, el inversionista realmente pierde
                            poder adquisitivo aunque reciba intereses.
                        </p>
                    </x-filament::section>
                </x-sections.contents.types>
            </div>

            {{-- Casos de Uso --}}
            <x-filament::section class="mt-8" heading="Casos de Uso" collapsible collapsed>
                <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                    <li><strong>Créditos:</strong> Determina cuánto terminarás pagando por un préstamo.</li>
                    <li><strong>Inversiones:</strong> Define la rentabilidad de depósitos o bonos.</li>
                    <li><strong>Ahorros:</strong> Permite calcular el crecimiento del capital en cuentas de ahorro.</li>
                    <li><strong>Tarjetas de crédito:</strong> Indica el costo de financiar consumos.</li>
                </ul>
            </x-filament::section>

            {{-- Ejemplos prácticos --}}
            <x-sections.contents.examples>
                <div class="space-y-4">
                    <x-sections.contents.example
                        title="📌 Ejemplo 1: Crédito"
                        description="Solicitas un préstamo de $10,000 al 12% anual nominal capitalizable mensualmente."
                        solution="i_m = 0.12 / 12 = 0.01 (1% mensual)"
                    />

                    <x-sections.contents.example
                        title="📌 Ejemplo 2: Inversión"
                        description="Inviertes $5,000 a una tasa efectiva anual del 8%."
                        solution="VF = 5000 × (1 + 0.08) = $5,400"
                    />

                    <x-sections.contents.example
                        title="📌 Ejemplo 3: Ajuste por inflación"
                        description="Una tasa nominal del 10% anual con inflación del 6%."
                        solution="i_real = (1.10 / 1.06) - 1 ≈ 3.77%"
                    />
                </div>

                <x-slot:advice>
                    💡 Consejo: Siempre compara las tasas <strong>efectivas</strong>, no solo las nominales, para evaluar el verdadero costo o rendimiento.
                </x-slot:advice>
            </x-sections.contents.examples>

            {{-- 🚀 Nueva sección: Interacción --}}
            <x-filament::section class="mt-8" heading="Interacción: Interés Simple vs Compuesto" collapsible>
                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <p>
                        El <strong>interés simple</strong> se calcula únicamente sobre el capital inicial.
                        Cada periodo se gana siempre la misma cantidad, sin importar el tiempo transcurrido.
                    </p>
                    <p class="font-mono text-green-500">
                        VF<sub>simple</sub> = C × (1 + i × t)
                    </p>

                    <p class="mt-4">
                        El <strong>interés compuesto</strong> reinvierte los intereses en cada periodo.
                        Esto provoca un <em>efecto bola de nieve</em>: cada vez se gana interés no solo sobre el capital inicial,
                        sino también sobre los intereses acumulados.
                    </p>
                    <p class="font-mono text-blue-500">
                        VF<sub>compuesto</sub> = C × (1 + i)<sup>t</sup>
                    </p>

                    <p class="mt-4">
                        Por eso, en la gráfica podrás observar que:
                        <ul class="list-disc list-inside">
                            <li>La curva del <span class="text-green-500 font-medium">interés simple</span> crece de manera lineal.</li>
                            <li>La curva del <span class="text-blue-500 font-medium">interés compuesto</span> crece de manera exponencial.</li>
                            <li>A medida que pasa el tiempo, la diferencia entre ambas se hace cada vez mayor.</li>
                        </ul>
                    </p>
                </div>

                {{-- Render del widget interactivo --}}
                <div class="mt-6">
                    @livewire(\App\Filament\Widgets\InteresInteractivoChart::class)
                </div>
            </x-filament::section>

        </x-sections.content>
    </div>
</x-filament-panels::page>
