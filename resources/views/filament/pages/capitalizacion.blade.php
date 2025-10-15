<x-filament-panels::page>
    <div class="space-y-6 min-h-[2200px]">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Sistemas de Capitalización"
            quote="“No definen cuánto se gana, sino cómo y cuándo los intereses se incorporan al capital.” — Leland Blank y Anthony Tarquin"
            button-text="Explorar Simulación"
            href="#simulacion"
        >
            <x-slot:icon>
                <x-heroicon-c-chart-bar class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Introducción --}}
        <x-sections.content title="¿Qué es la Capitalización?" class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-green-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4"></h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            La <strong class="text-green-600 dark:text-green-400">capitalización</strong> es el proceso mediante el cual los intereses generados por una inversión
                            se añaden al capital inicial, generando así nuevos intereses en períodos sucesivos.
                            Es el principio fundamental detrás del crecimiento exponencial del dinero.
                        </p>
                        <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📈 El Poder del Interés Compuesto</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <strong>"Interés sobre interés":</strong> Los rendimientos generan nuevos rendimientos
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <strong>Crecimiento exponencial:</strong> El dinero crece cada vez más rápido
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <strong>El tiempo es clave:</strong> A mayor plazo, mayor el efecto
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">🎯 Variables Clave en Capitalización</h4>
                        <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <strong>Capital Inicial (VP):</strong> Monto inicial de la inversión
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <strong>Tasa de Interés (i):</strong> Porcentaje de rendimiento por período
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <strong>Tiempo (n):</strong> Número de períodos de capitalización
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <strong>Frecuencia:</strong> Veces que se capitaliza en un año
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Comparación Visual Simple vs Compuesto --}}
        <x-sections.content collapsed="true" title="📊 El Efecto Diferencial: Simple vs Compuesto" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <div class="">

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Gráfico conceptual --}}
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Crecimiento Comparativo</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Año 1</span>
                                <div class="flex space-x-2">
                                    <div class="w-16 h-4 bg-blue-200 rounded" title="Simple: $1,100"></div>
                                    <div class="w-16 h-4 bg-blue-200 rounded" title="Compuesto: $1,100"></div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Año 5</span>
                                <div class="flex space-x-2">
                                    <div class="w-24 h-4 bg-blue-300 rounded" title="Simple: $1,500"></div>
                                    <div class="w-32 h-4 bg-green-300 rounded" title="Compuesto: $1,611"></div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Año 10</span>
                                <div class="flex space-x-2">
                                    <div class="w-32 h-4 bg-blue-400 rounded" title="Simple: $2,000"></div>
                                    <div class="w-48 h-4 bg-green-400 rounded" title="Compuesto: $2,594"></div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Año 20</span>
                                <div class="flex space-x-2">
                                    <div class="w-40 h-4 bg-blue-500 rounded" title="Simple: $3,000"></div>
                                    <div class="w-64 h-4 bg-green-500 rounded" title="Compuesto: $6,727"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-4 text-xs">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded mr-1"></div>
                                <span>Interés Simple</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded mr-1"></div>
                                <span>Interés Compuesto</span>
                            </div>
                        </div>
                    </div>

                    {{-- Explicación del efecto --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">💡 El "Efecto Bola de Nieve"</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Mientras el interés simple crece de forma lineal, el interés compuesto acelera su crecimiento con el tiempo,
                            creando una curva exponencial que se hace más pronunciada en cada período.
                        </p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded">
                            <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                                <strong>Dato Curioso:</strong> $100 invertidos al 10% anual durante 50 años:<br>
                                • Simple: $600 → • Compuesto: $11,739 → <strong>20 veces más!</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Sistemas de Capitalización Detallados --}}
        <x-sections.content collapsed="true" title="🏦 Sistemas de Capitalización">

            {{-- Capitalización Simple --}}
            <div class="mb-8">
                <x-filament::section heading="📈 Capitalización Simple" collapsible="true" collapsed="false">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <strong>Intereses constantes</strong> en cada período
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <strong>Crecimiento lineal</strong> del capital
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        Los intereses <strong>no generan nuevos intereses</strong>
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        Usado en préstamos a corto plazo
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Fórmula Principal</h4>
                                <p class="text-blue-800 dark:text-blue-200 font-mono text-sm">
                                    VF = VP × (1 + i × n)
                                </p>
                                <div class="mt-3 text-xs text-blue-700 dark:text-blue-300">
                                    <p><strong>VF:</strong> Valor Futuro</p>
                                    <p><strong>VP:</strong> Valor Presente (capital inicial)</p>
                                    <p><strong>i:</strong> Tasa de interés periódica (decimal)</p>
                                    <p><strong>n:</strong> Número de períodos</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Detallado Capitalización Simple --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo Práctico: Inversión de $1,000 al 10% anual por 5 años</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Año</th>
                                        <th class="px-4 py-2 text-right">Capital Inicial</th>
                                        <th class="px-4 py-2 text-right">Interés Anual</th>
                                        <th class="px-4 py-2 text-right">Capital Final</th>
                                        <th class="px-4 py-2 text-right">Interés Acumulado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,100.00</td>
                                        <td class="px-4 py-2 text-right">$100.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">2</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,200.00</td>
                                        <td class="px-4 py-2 text-right">$200.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">3</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,300.00</td>
                                        <td class="px-4 py-2 text-right">$300.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">4</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,400.00</td>
                                        <td class="px-4 py-2 text-right">$400.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">5</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,500.00</td>
                                        <td class="px-4 py-2 text-right">$500.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    <strong>Nota:</strong> En capitalización simple, el interés siempre se calcula sobre el capital inicial ($1,000),
                                    por lo que el interés anual es constante en $100.
                                </p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Capitalización Compuesta --}}
            <div class="mb-8">
                <x-filament::section heading="🚀 Capitalización Compuesta" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <strong>Intereses crecientes</strong> en cada período
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <strong>Crecimiento exponencial</strong> del capital
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Los intereses <strong>generan nuevos intereses</strong>
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Base de la mayoría de inversiones modernas
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Fórmula Principal</h4>
                                <p class="text-green-800 dark:text-green-200 font-mono text-sm">
                                    VF = VP × (1 + i)ⁿ
                                </p>
                                <div class="mt-3 text-xs text-green-700 dark:text-green-300">
                                    <p><strong>VF:</strong> Valor Futuro</p>
                                    <p><strong>VP:</strong> Valor Presente</p>
                                    <p><strong>i:</strong> Tasa de interés periódica</p>
                                    <p><strong>n:</strong> Número de períodos</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Detallado Capitalización Compuesta --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo Práctico: Inversión de $1,000 al 10% anual por 5 años</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Año</th>
                                        <th class="px-4 py-2 text-right">Capital Inicial</th>
                                        <th class="px-4 py-2 text-right">Interés Anual</th>
                                        <th class="px-4 py-2 text-right">Capital Final</th>
                                        <th class="px-4 py-2 text-right">Interés Acumulado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1</td>
                                        <td class="px-4 py-2 text-right">$1,000.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$100.00</td>
                                        <td class="px-4 py-2 text-right">$1,100.00</td>
                                        <td class="px-4 py-2 text-right">$100.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">2</td>
                                        <td class="px-4 py-2 text-right">$1,100.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$110.00</td>
                                        <td class="px-4 py-2 text-right">$1,210.00</td>
                                        <td class="px-4 py-2 text-right">$210.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">3</td>
                                        <td class="px-4 py-2 text-right">$1,210.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$121.00</td>
                                        <td class="px-4 py-2 text-right">$1,331.00</td>
                                        <td class="px-4 py-2 text-right">$331.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">4</td>
                                        <td class="px-4 py-2 text-right">$1,331.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$133.10</td>
                                        <td class="px-4 py-2 text-right">$1,464.10</td>
                                        <td class="px-4 py-2 text-right">$464.10</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">5</td>
                                        <td class="px-4 py-2 text-right">$1,464.10</td>
                                        <td class="px-4 py-2 text-right text-green-500">$146.41</td>
                                        <td class="px-4 py-2 text-right">$1,610.51</td>
                                        <td class="px-4 py-2 text-right">$610.51</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                <p class="text-green-800 dark:text-green-200 text-sm">
                                    <strong>Nota:</strong> En capitalización compuesta, el interés se calcula sobre el capital acumulado,
                                    por lo que el interés aumenta cada año. ¡En el año 5 el interés es $146.41 vs $100 del simple!
                                </p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Capitalización Continua --}}
            <div class="mb-8">
                <x-filament::section heading="⚡ Capitalización Continua" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        <strong>Capitalización instantánea</strong> y constante
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        <strong>Máximo crecimiento</strong> posible para una tasa dada
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        Utiliza el <strong>número e</strong> (2.71828...)
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        Usado en modelos financieros avanzados
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">Fórmula Principal</h4>
                                <p class="text-purple-800 dark:text-purple-200 font-mono text-sm">
                                    VF = VP × e^(i × n)
                                </p>
                                <div class="mt-3 text-xs text-purple-700 dark:text-purple-300">
                                    <p><strong>VF:</strong> Valor Futuro</p>
                                    <p><strong>VP:</strong> Valor Presente</p>
                                    <p><strong>e:</strong> Constante matemática ≈ 2.71828</p>
                                    <p><strong>i:</strong> Tasa de interés nominal anual</p>
                                    <p><strong>n:</strong> Número de años</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Comparativo --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📊 Comparación: $1,000 al 10% anual por 5 años</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Sistema</th>
                                        <th class="px-4 py-2 text-right">Fórmula</th>
                                        <th class="px-4 py-2 text-right">Valor Final</th>
                                        <th class="px-4 py-2 text-right">Interés Total</th>
                                        <th class="px-4 py-2 text-right">Diferencia vs Simple</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2 font-medium">Simple</td>
                                        <td class="px-4 py-2 text-right font-mono text-xs">1000×(1+0.10×5)</td>
                                        <td class="px-4 py-2 text-right">$1,500.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$500.00</td>
                                        <td class="px-4 py-2 text-right">-</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2 font-medium">Compuesto Anual</td>
                                        <td class="px-4 py-2 text-right font-mono text-xs">1000×(1+0.10)⁵</td>
                                        <td class="px-4 py-2 text-right">$1,610.51</td>
                                        <td class="px-4 py-2 text-right text-green-500">$610.51</td>
                                        <td class="px-4 py-2 text-right text-blue-500">+$110.51</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2 font-medium">Compuesto Mensual</td>
                                        <td class="px-4 py-2 text-right font-mono text-xs">1000×(1+0.10/12)⁶⁰</td>
                                        <td class="px-4 py-2 text-right">$1,645.31</td>
                                        <td class="px-4 py-2 text-right text-green-500">$645.31</td>
                                        <td class="px-4 py-2 text-right text-blue-500">+$145.31</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-medium">Continua</td>
                                        <td class="px-4 py-2 text-right font-mono text-xs">1000×e^(0.10×5)</td>
                                        <td class="px-4 py-2 text-right font-semibold">$1,648.72</td>
                                        <td class="px-4 py-2 text-right text-green-500 font-semibold">$648.72</td>
                                        <td class="px-4 py-2 text-right text-purple-500 font-semibold">+$148.72</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 p-3 bg-purple-50 dark:bg-purple-900/20 rounded">
                                <p class="text-purple-800 dark:text-purple-200 text-sm">
                                    <strong>Nota:</strong> La capitalización continua representa el límite teórico máximo de crecimiento.
                                    A mayor frecuencia de capitalización, más nos acercamos a este límite.
                                </p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </x-sections.content>

        {{-- Frecuencias de Capitalización --}}
        <x-sections.content collapsed="true" title="🔄 Frecuencias de Capitalización" class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-orange-200 dark:border-gray-700">
            <div>
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Tabla de frecuencias --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Comparación de Frecuencias</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-3 py-2 text-left">Frecuencia</th>
                                    <th class="px-3 py-2 text-right">Períodos/Año</th>
                                    <th class="px-3 py-2 text-right">Tasa Efectiva*</th>
                                    <th class="px-3 py-2 text-right">Valor Final</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">Anual</td>
                                    <td class="px-3 py-2 text-right">1</td>
                                    <td class="px-3 py-2 text-right">10.000%</td>
                                    <td class="px-3 py-2 text-right">$1,610.51</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">Semestral</td>
                                    <td class="px-3 py-2 text-right">2</td>
                                    <td class="px-3 py-2 text-right">10.250%</td>
                                    <td class="px-3 py-2 text-right">$1,628.89</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">Trimestral</td>
                                    <td class="px-3 py-2 text-right">4</td>
                                    <td class="px-3 py-2 text-right">10.381%</td>
                                    <td class="px-3 py-2 text-right">$1,638.62</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">Mensual</td>
                                    <td class="px-3 py-2 text-right">12</td>
                                    <td class="px-3 py-2 text-right">10.471%</td>
                                    <td class="px-3 py-2 text-right">$1,645.31</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">Diaria</td>
                                    <td class="px-3 py-2 text-right">365</td>
                                    <td class="px-3 py-2 text-right">10.516%</td>
                                    <td class="px-3 py-2 text-right">$1,648.61</td>
                                </tr>
                                <tr class="bg-green-50 dark:bg-green-900/20">
                                    <td class="px-3 py-2 font-semibold">Continua</td>
                                    <td class="px-3 py-2 text-right">∞</td>
                                    <td class="px-3 py-2 text-right font-semibold">10.517%</td>
                                    <td class="px-3 py-2 text-right font-semibold">$1,648.72</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            *Tasa nominal del 10% anual, $1,000 invertidos por 5 años
                        </p>
                    </div>

                    {{-- Explicación Tasa Efectiva --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">🎯 Tasa Nominal vs Tasa Efectiva</h4>
                        <div class="space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100">Tasa Nominal (iₙ)</h5>
                                <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                                    Es la tasa declarada, sin considerar la frecuencia de capitalización.<br>
                                    <strong>Ejemplo:</strong> 12% anual capitalizable trimestralmente
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-green-900 dark:text-green-100">Tasa Efectiva (iₑ)</h5>
                                <p class="text-sm text-green-800 dark:text-green-200 mt-1">
                                    Es la tasa real que se gana después de considerar la capitalización.<br>
                                    <strong>Fórmula:</strong> iₑ = (1 + iₙ/m)ᵐ - 1
                                </p>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-orange-900 dark:text-orange-100">Ejemplo Práctico</h5>
                                <p class="text-sm text-orange-800 dark:text-orange-200 mt-1">
                                    12% nominal trimestral = 12.55% efectivo anual<br>
                                    <strong>Cálculo:</strong> (1 + 0.12/4)⁴ - 1 = 0.1255 = 12.55%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Aplicaciones en Inversiones Reales --}}
        <x-sections.content collapsed="true" title="💼 Aplicaciones en el Mundo Real" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    {{-- Fondos de Inversión --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">📈 Fondos Mutuos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Capitalización:</strong> Compuesta diaria<br>
                            <strong>Ejemplo:</strong> Reinversión automática de dividendos<br>
                            <strong>Ventaja:</strong> Crecimiento exponencial a largo plazo
                        </p>
                    </div>

                    {{-- Certificados Financieros --}}
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-document-text class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">🏦 Certificados de Depósito</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Capitalización:</strong> Compuesta trimestral/mensual<br>
                            <strong>Ejemplo:</strong> CD a 1-5 años con interés compuesto<br>
                            <strong>Ventaja:</strong> Rendimiento predecible y seguro
                        </p>
                    </div>

                    {{-- Planes de Pensiones --}}
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-building-library class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">🏛️ Planes de Jubilación</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Capitalización:</strong> Compuesta con aportes periódicos<br>
                            <strong>Ejemplo:</strong> 401(k), IRA con contribuciones automáticas<br>
                            <strong>Ventaja:</strong> Efecto bola de nieve durante décadas
                        </p>
                    </div>
                </div>

                {{-- Caso de Estudio: Inversión a Largo Plazo --}}
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">🎯 Caso de Estudio: El Poder del Tiempo</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Escenario: $10,000 al 8% anual</h5>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span>10 años:</span>
                                    <span class="font-semibold">$21,589</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>20 años:</span>
                                    <span class="font-semibold">$46,610</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>30 años:</span>
                                    <span class="font-semibold text-green-600">$100,627</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>40 años:</span>
                                    <span class="font-semibold text-green-600">$217,245</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">💡 Lección Principal</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Los últimos 10 años generan más dinero que los primeros 30 juntos.</strong><br>
                                Esto demuestra por qué empezar temprano es tan crucial en las inversiones.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Calculadora --}}
        <x-sections.content
            id="simulacion"
            title="Interacción: Interés Simple vs Compuesto"
            :collapsed="false">

            <div class="space-y-6 text-gray-700 dark:text-gray-300">
                <p class="mt-2 text-lg font-medium mx-auto text-center">
                    A continuación, podrás explorar gráficamente cómo esta diferencia impacta en el crecimiento del capital a lo largo del tiempo.
                </p>

                {{-- Render del widget interactivo --}}
                <livewire:app.filament.widgets.interes-interactivo-chart />
            </div>
        </x-sections.content>

        {{-- Consejos de Inversión --}}
        <x-sections.content title="💎 Consejos para Maximizar la Capitalización" class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-indigo-200 dark:border-gray-700">
            <div class="">

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">⏰</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Empieza Temprano</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Cada año de demora reduce dramáticamente tu patrimonio final.
                                    El tiempo es el ingrediente más importante en la capitalización compuesta.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">🔄</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Reinvierte los Rendimientos</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Nunca retires los intereses ganados. Dejarlos invertidos es lo que
                                    activa el verdadero poder del interés compuesto.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">📅</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Aporta Regularmente</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Las contribuciones periódicas (dollar-cost averaging)
                                    multiplican el efecto de la capitalización y reducen el riesgo.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">🎯</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Minimiza las Comisiones</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Incluso comisiones pequeñas tienen un gran impacto compuesto a largo plazo.
                                    Busca vehículos de inversión con bajos costos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Regla del 72 --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">🎓 La Regla del 72</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                Una forma rápida de estimar cuánto tiempo toma duplicar tu inversión:
                            </p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 text-center">
                                72 ÷ tasa de interés = años para duplicar
                            </p>
                        </div>
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                            <p class="text-sm text-indigo-800 dark:text-indigo-200">
                                <strong>Ejemplos:</strong><br>
                                • 6% anual: 72 ÷ 6 = 12 años<br>
                                • 8% anual: 72 ÷ 8 = 9 años<br>
                                • 12% anual: 72 ÷ 12 = 6 años
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
