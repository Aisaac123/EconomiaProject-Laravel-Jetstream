{{-- resources/views/filament/pages/tasa-interes.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Hero Section --}}
        <x-sections.heading-title
            title="Tasa de Interés"
            quote="“Comprender la tasa de interés es entender el costo del dinero, y por tanto, el corazón de toda inversión racional.” — Benjamin Graham"
            button-text="Explorar conceptos"
            href="#conceptos"
        >
            <x-slot:icon>
                <x-heroicon-c-currency-dollar class="size-16 text-white" aria-hidden="true"/>
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Contenido principal --}}
        <x-sections.content id="conceptos" :is-collapsible="false">

            {{-- Descripción --}}
            <x-sections.contents.description>
                <p>
                    La <strong>tasa de interés</strong> es el porcentaje que se paga o recibe por el uso del dinero en
                    un periodo de tiempo.
                    Representa el <span class="text-primary-600 dark:text-primary-400 font-medium">costo de pedir prestado</span>
                    o el <span class="text-primary-600 dark:text-primary-400 font-medium">beneficio de invertir</span>.
                </p>
                <p class="mt-2">
                    Se utiliza en créditos, ahorros, inversiones, tarjetas de crédito y prácticamente en todas las
                    operaciones financieras.
                    En otras palabras, la tasa de interés es la forma en que se mide cuánto crece o cuesta el dinero a
                    lo largo del tiempo.
                </p>
            </x-sections.contents.description>

            {{-- Tipos de Tasa de Interés --}}
            <div class="pt-2 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tipos de Tasa de Interés --}}
                <div>
                    <x-sections.contents.types body-class="text-gray-700 dark:text-gray-300 space-y-4"
                                               title="Tipos de Tasa de Interés">

                        <x-filament::section collapsed="true" heading="Tasa Nominal" collapsible="true">
                            <p class="text-gray-600 dark:text-gray-300">
                                Es la tasa expresada sin tener en cuenta la capitalización.
                                Por ejemplo, un 12% anual nominal puede capitalizarse mensual, trimestral, etc.
                            </p>
                            <p class="mt-2 font-mono font-semibold text-primary-600 dark:text-primary-400">
                                i<sub>n</sub> = r / m
                            </p>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">
                                Donde <strong>r</strong> es la tasa anual y <strong>m</strong> es el número de periodos
                                en que se divide el año.
                            </p>
                        </x-filament::section>

                        <x-filament::section collapsed="true" heading="Tasa Efectiva" collapsible="true">
                            <p class="text-gray-600 dark:text-gray-300">
                                Refleja el costo real de un préstamo o la ganancia real de una inversión,
                                considerando la capitalización de los intereses.
                            </p>
                            <p class="mt-2 font-mono font-semibold text-primary-600 dark:text-primary-400">
                                i<sub>ef</sub> = (1 + r/m)<sup>m</sup> - 1
                            </p>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">
                                Esta es la tasa que se debe usar para comparar alternativas, ya que muestra
                                el verdadero crecimiento del dinero en el tiempo.
                            </p>
                        </x-filament::section>

                        <x-filament::section collapsed="true" heading="Tasa Real" collapsible="true">
                            <p class="text-gray-600 dark:text-gray-300">
                                Ajusta la tasa de interés considerando la inflación.
                                Indica el crecimiento real del poder adquisitivo.
                            </p>
                            <p class="mt-2 font-mono font-semibold text-primary-600 dark:text-primary-400">
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
                <div class="space-y-4">
                    <p class="text-lg font-semibold text-primary-700 dark:text-primary-300 mb-2">
                        Casos de Uso
                    </p>

                    <x-filament::section collapsed="true" heading="Créditos" collapsible="true">
                        <p class="text-gray-700 dark:text-gray-300">
                            Determina cuánto terminarás pagando por un préstamo. La tasa de interés aquí afecta
                            directamente
                            el costo total del crédito, incluyendo cuotas mensuales y pago final.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <strong>Ejemplo:</strong> Un préstamo de $10,000 con tasa nominal anual del 12% puede
                            generar pagos mensuales de $888 si se capitaliza mensual.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Considera siempre comparar la tasa nominal vs. la efectiva para conocer el verdadero costo.
                        </p>
                    </x-filament::section>

                    <x-filament::section collapsed="true" heading="Inversiones" collapsible="true">
                        <p class="text-gray-700 dark:text-gray-300">
                            Define la rentabilidad de depósitos, bonos o instrumentos financieros. Una tasa de interés
                            más alta
                            incrementa el crecimiento de tu inversión con el tiempo.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Prefiere tasas efectivas para comparar diferentes alternativas, ya que reflejan la
                            capitalización real.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <strong>Ejemplo:</strong> práctico: Un depósito a 12 meses con tasa efectiva anual del 10%
                            transformará $1,000 en $1,100 al final del periodo.
                        </p>
                    </x-filament::section>

                    <x-filament::section collapsed="true" heading="Ahorros" collapsible="true">
                        <p class="text-gray-700 dark:text-gray-300">
                            Permite calcular el crecimiento del capital en cuentas de ahorro. La tasa de interés
                            determina cuánto tu dinero genera intereses
                            con el tiempo.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <strong>Ejemplo:</strong> Una cuenta de ahorro con $500 a una tasa efectiva del 5% anual
                            rendirá $525 al cabo de un año.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Es importante comparar diferentes bancos y tipos de interés para maximizar el rendimiento de
                            tu ahorro.
                        </p>
                    </x-filament::section>

                    <x-filament::section collapsed="true" heading="Tarjetas de Crédito" collapsible="true">
                        <p class="text-gray-700 dark:text-gray-300">
                            Indica el costo de financiar consumos. La tasa de interés es crítica aquí, ya que puede
                            hacer que una deuda crezca rápidamente si no se paga a tiempo.
                        </p>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            <strong>Ejemplo:</strong> Una tarjeta con tasa nominal mensual del 3% implica que un saldo
                            de $1,000 se incrementará a $1,030 en un mes si no se paga.
                        </p>
                    </x-filament::section>
                </div>


            </div>

            {{-- 🚀 Nueva sección: Interacción --}}
            <x-sections.contents.examples
                :has-bg="false"
                class="mt-8"
                title="Interacción: Interés Simple vs Compuesto"
                :collapsed="false">

                <div class="space-y-6 text-gray-700 dark:text-gray-300">
                    <p class="text-base">
                        Aunque dos inversiones comiencen con el mismo capital y la misma tasa, su evolución puede ser sorprendentemente diferente.
                        La clave está en <span class="font-semibold text-primary-500">cómo se aplican los intereses a lo largo del tiempo</span>.
                    </p>

                    <p class="mt-2 text-base">
                        En algunos casos, los intereses se suman de manera constante, produciendo un crecimiento predecible y lineal.
                        En otros, los intereses se reinvierten, generando un efecto acumulativo donde el capital se incrementa sobre lo ya acumulado,
                        ampliando con el tiempo la diferencia entre ambos métodos.
                        Esta dinámica explica por qué, a largo plazo, la forma de capitalización puede transformar de manera significativa los resultados financieros.
                    </p>

                    {{-- Explicación comparativa con estilo cortina --}}
                    <x-filament::section class="mt-10" heading="Comparativa Conceptual: Interés Simple vs Interés Compuesto" collapsible collapsed>
                        <div class="space-y-4">
                            <p>
                                El <strong>interés simple</strong> se calcula únicamente sobre el <em>capital inicial</em>,
                                lo que genera incrementos
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">constantes y lineales</span>.
                                Cada periodo añade siempre la misma ganancia, sin importar cuánto tiempo transcurra.
                            </p>
                            <p>
                                En cambio, el <strong>interés compuesto</strong> toma como base no solo el capital inicial,
                                sino también los intereses previamente acumulados.
                                Esto origina un crecimiento <span class="font-semibold text-teal-600 dark:text-teal-400">acelerado y exponencial</span>,
                                porque los intereses generan a su vez nuevos intereses.
                            </p>
                            <p>
                                Por ello, mientras el interés simple avanza en línea recta,
                                el interés compuesto se expande cada vez con mayor rapidez,
                                generando una diferencia progresiva que, con el paso de los años,
                                puede ser <span class="font-semibold text-amber-600 dark:text-amber-400">determinante en la rentabilidad o en el costo de una operación financiera</span>.
                            </p>
                        </div>
                    </x-filament::section>

                    <p class="mt-2 text-lg font-medium">
                        A continuación, podrás explorar gráficamente cómo esta diferencia impacta en el crecimiento del capital a lo largo del tiempo.
                    </p>

                    {{-- Render del widget interactivo --}}
                    <livewire:app.filament.widgets.interes-interactivo-chart />
                </div>
            </x-sections.contents.examples>


        </x-sections.content>
    </div>
</x-filament-panels::page>
