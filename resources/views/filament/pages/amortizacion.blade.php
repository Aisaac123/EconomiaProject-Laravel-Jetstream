<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Sistemas de Amortización"
            quote="“El secreto no está en cuánto ganas, sino en cómo manejas lo que debes.”  — Robert Kiyosaki"
            button-text="Explorar Calculadora"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-calculator class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Introducción --}}
        <x-sections.content title="¿Qué es la Amortización?" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-blue-200 dark:border-gray-700">
            <div class="">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            La <strong class="text-blue-600 dark:text-blue-400">amortización</strong> es el proceso financiero de distribuir un préstamo en una serie de pagos periódicos a lo largo del tiempo.
                            Cada pago incluye una porción para el pago de intereses y otra para reducir el capital pendiente.
                        </p>
                        <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📊 Componentes de un Pago:</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <strong>Capital:</strong> Reduce la deuda principal
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    <strong>Intereses:</strong> Costo del préstamo en el período
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                    <strong>Saldo:</strong> Deuda restante después del pago
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">🎯 Objetivos de la Amortización</h4>
                        <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                Planificar el pago de deudas de manera sistemática
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                Distribuir el costo del préstamo en el tiempo
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                Prever el flujo de caja para pagos futuros
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                Reducir progresivamente el riesgo crediticio
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Sistemas de Amortización Detallados --}}
        <x-sections.content collapsed="true" title="🏦 Sistemas de Amortización Principales">

            {{-- Sistema Francés --}}
            <div class="mb-8">
                <x-filament::section heading="🏦 Sistema Francés (Cuota Constante)" collapsible="true" collapsed="false">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <strong>Cuota constante </strong> durante todo el plazo
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <strong>Intereses decrecientes</strong> en cada período
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        <strong>Amortización creciente</strong> progresivamente
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        Más utilizado a nivel mundial
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Fórmula Principal</h4>
                                <p class="text-blue-800 dark:text-blue-200 font-mono text-sm">
                                    Cuota = P × [r(1 + r)ⁿ] / [(1 + r)ⁿ - 1]
                                </p>
                                <div class="mt-3 text-xs text-blue-700 dark:text-blue-300">
                                    <p><strong>P:</strong> Principal del préstamo</p>
                                    <p><strong>r:</strong> Tasa de interés periódica</p>
                                    <p><strong>n:</strong> Número de períodos</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Detallado Sistema Francés --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo Práctico: Préstamo de $10,000 a 5 años (60 meses) al 12% anual</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Mes</th>
                                        <th class="px-4 py-2 text-right">Cuota</th>
                                        <th class="px-4 py-2 text-right">Interés</th>
                                        <th class="px-4 py-2 text-right">Amortización</th>
                                        <th class="px-4 py-2 text-right">Saldo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1</td>
                                        <td class="px-4 py-2 text-right">$222.44</td>
                                        <td class="px-4 py-2 text-right text-red-500">$100.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$122.44</td>
                                        <td class="px-4 py-2 text-right">$9,877.56</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">12</td>
                                        <td class="px-4 py-2 text-right">$222.44</td>
                                        <td class="px-4 py-2 text-right text-red-500">$85.21</td>
                                        <td class="px-4 py-2 text-right text-green-500">$137.23</td>
                                        <td class="px-4 py-2 text-right">$8,456.78</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">36</td>
                                        <td class="px-4 py-2 text-right">$222.44</td>
                                        <td class="px-4 py-2 text-right text-red-500">$45.67</td>
                                        <td class="px-4 py-2 text-right text-green-500">$176.77</td>
                                        <td class="px-4 py-2 text-right">$4,123.45</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">60</td>
                                        <td class="px-4 py-2 text-right">$222.44</td>
                                        <td class="px-4 py-2 text-right text-red-500">$2.21</td>
                                        <td class="px-4 py-2 text-right text-green-500">$220.23</td>
                                        <td class="px-4 py-2 text-right">$0.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Sistema Alemán --}}
            <div class="mb-8">
                <x-filament::section heading="🇩🇪 Sistema Alemán (Amortización Constante)" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <strong>Amortización constante</strong> en cada período
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <strong>Intereses decrecientes</strong> sobre saldo reducido
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <strong>Cuotas decrecientes</strong> progresivamente
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Ideal para ingresos crecientes
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Fórmulas Principales</h4>
                                <p class="text-green-800 dark:text-green-200 font-mono text-sm">
                                    Amortización = P / n
                                </p>
                                <p class="text-green-800 dark:text-green-200 font-mono text-sm mt-2">
                                    Interés = Saldo × r
                                </p>
                                <div class="mt-3 text-xs text-green-700 dark:text-green-300">
                                    <p><strong>P:</strong> Principal del préstamo</p>
                                    <p><strong>n:</strong> Número de períodos</p>
                                    <p><strong>r:</strong> Tasa de interés periódica</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Detallado Sistema Alemán --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo Práctico: Préstamo de $10,000 a 5 años (60 meses) al 12% anual</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Mes</th>
                                        <th class="px-4 py-2 text-right">Cuota</th>
                                        <th class="px-4 py-2 text-right">Interés</th>
                                        <th class="px-4 py-2 text-right">Amortización</th>
                                        <th class="px-4 py-2 text-right">Saldo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1</td>
                                        <td class="px-4 py-2 text-right">$266.67</td>
                                        <td class="px-4 py-2 text-right text-red-500">$100.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$166.67</td>
                                        <td class="px-4 py-2 text-right">$9,833.33</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">12</td>
                                        <td class="px-4 py-2 text-right">$250.00</td>
                                        <td class="px-4 py-2 text-right text-red-500">$83.33</td>
                                        <td class="px-4 py-2 text-right text-green-500">$166.67</td>
                                        <td class="px-4 py-2 text-right">$8,000.00</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">36</td>
                                        <td class="px-4 py-2 text-right">$200.00</td>
                                        <td class="px-4 py-2 text-right text-red-500">$33.33</td>
                                        <td class="px-4 py-2 text-right text-green-500">$166.67</td>
                                        <td class="px-4 py-2 text-right">$4,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">60</td>
                                        <td class="px-4 py-2 text-right">$168.33</td>
                                        <td class="px-4 py-2 text-right text-red-500">$1.67</td>
                                        <td class="px-4 py-2 text-right text-green-500">$166.67</td>
                                        <td class="px-4 py-2 text-right">$0.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Sistema Americano --}}
            <div class="mb-8">
                <x-filament::section heading="🇺🇸 Sistema Americano (Pago Periódico de Intereses)" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Características Principales</h4>
                                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"> </span>
                                        <strong>Solo intereses</strong> durante el plazo
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        <strong>Pago total del capital</strong> al vencimiento
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        <strong>Cuotas bajas</strong> durante el período
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        Requiere fondo de amortización
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-red-900 dark:text-red-100 mb-2">Fórmulas Principales</h4>
                                <p class="text-red-800 dark:text-red-200 font-mono text-sm">
                                    Cuota Periódica = P × r
                                </p>
                                <p class="text-red-800 dark:text-red-200 font-mono text-sm mt-2">
                                    Cuota Final = P
                                </p>
                                <div class="mt-3 text-xs text-red-700 dark:text-red-300">
                                    <p><strong>P:</strong> Principal del préstamo</p>
                                    <p><strong>r:</strong> Tasa de interés periódica</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Detallado Sistema Americano --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo Práctico: Préstamo de $10,000 a 5 años (60 meses) al 12% anual</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Mes</th>
                                        <th class="px-4 py-2 text-right">Cuota</th>
                                        <th class="px-4 py-2 text-right">Interés</th>
                                        <th class="px-4 py-2 text-right">Amortización</th>
                                        <th class="px-4 py-2 text-right">Saldo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1-59</td>
                                        <td class="px-4 py-2 text-right">$100.00</td>
                                        <td class="px-4 py-2 text-right text-red-500">$100.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$0.00</td>
                                        <td class="px-4 py-2 text-right">$10,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">60</td>
                                        <td class="px-4 py-2 text-right">$10,100.00</td>
                                        <td class="px-4 py-2 text-right text-red-500">$100.00</td>
                                        <td class="px-4 py-2 text-right text-green-500">$10,000.00</td>
                                        <td class="px-4 py-2 text-right">$0.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </x-sections.content>

        {{-- Comparativa de Sistemas --}}
        <x-sections.content collapsed="true" title="📊 Comparativa de Sistemas de Amortización">
            <div class="">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6"></h2>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    {{-- Sistema Francés --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                        <h3 class="font-bold text-blue-900 dark:text-blue-100 text-lg mb-3">🏦 Sistema Francés</h3>
                        <div class="space-y-2 text-sm">
                            <p class="text-blue-800 dark:text-blue-200"><strong>Ventajas:</strong></p>
                            <ul class="list-disc list-inside text-blue-700 dark:text-blue-300">
                                <li>Cuota constante facilita presupuesto</li>
                                <li>Amortización progresiva</li>
                                <li>Más utilizado mundialmente</li>
                            </ul>
                            <p class="text-blue-800 dark:text-blue-200 mt-3"><strong>Desventajas:</strong></p>
                            <ul class="list-disc list-inside text-blue-700 dark:text-blue-300">
                                <li>Mayor costo total en intereses</li>
                                <li>Lenta reducción inicial de capital</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Sistema Alemán --}}
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                        <h3 class="font-bold text-green-900 dark:text-green-100 text-lg mb-3">🇩🇪 Sistema Alemán</h3>
                        <div class="space-y-2 text-sm">
                            <p class="text-green-800 dark:text-green-200"><strong>Ventajas:</strong></p>
                            <ul class="list-disc list-inside text-green-700 dark:text-green-300">
                                <li>Menor costo total en intereses</li>
                                <li>Amortización constante desde inicio</li>
                                <li>Ideal para ingresos crecientes</li>
                            </ul>
                            <p class="text-green-800 dark:text-green-200 mt-3"><strong>Desventajas:</strong></p>
                            <ul class="list-disc list-inside text-green-700 dark:text-green-300">
                                <li>Cuotas iniciales más altas</li>
                                <li>Menor flexibilidad presupuestaria</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Sistema Americano --}}
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                        <h3 class="font-bold text-red-900 dark:text-red-100 text-lg mb-3">🇺🇸 Sistema Americano</h3>
                        <div class="space-y-2 text-sm">
                            <p class="text-red-800 dark:text-red-200"><strong>Ventajas:</strong></p>
                            <ul class="list-disc list-inside text-red-700 dark:text-red-300">
                                <li>Cuotas periódicas muy bajas</li>
                                <li>Ideal para liquidez temporal</li>
                                <li>Flexibilidad en el corto plazo</li>
                            </ul>
                            <p class="text-red-800 dark:text-red-200 mt-3"><strong>Desventajas:</strong></p>
                            <ul class="list-disc list-inside text-red-700 dark:text-red-300">
                                <li>Riesgo de pago final elevado</li>
                                <li>Requiere fondo de amortización</li>
                                <li>Mayor riesgo crediticio</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Tabla Comparativa --}}
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">📈 Comparación Numérica: Préstamo $10,000 a 5 años al 12% anual</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="px-4 py-3 text-left">Sistema</th>
                                <th class="px-4 py-3 text-right">Cuota Inicial</th>
                                <th class="px-4 py-3 text-right">Cuota Final</th>
                                <th class="px-4 py-3 text-right">Total Intereses</th>
                                <th class="px-4 py-3 text-right">Costo Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-3 font-medium">Francés</td>
                                <td class="px-4 py-3 text-right">$222.44</td>
                                <td class="px-4 py-3 text-right">$222.44</td>
                                <td class="px-4 py-3 text-right text-red-500">$3,346.40</td>
                                <td class="px-4 py-3 text-right">$13,346.40</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-3 font-medium">Alemán</td>
                                <td class="px-4 py-3 text-right">$266.67</td>
                                <td class="px-4 py-3 text-right">$168.33</td>
                                <td class="px-4 py-3 text-right text-red-500">$3,250.00</td>
                                <td class="px-4 py-3 text-right">$13,250.00</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Americano</td>
                                <td class="px-4 py-3 text-right">$100.00</td>
                                <td class="px-4 py-3 text-right">$10,100.00</td>
                                <td class="px-4 py-3 text-right text-red-500">$6,000.00</td>
                                <td class="px-4 py-3 text-right">$16,000.00</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Aplicaciones Prácticas --}}
        <x-sections.content collapsed="true" title="💼 Aplicaciones Prácticas en la Vida Real" class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-purple-200 dark:border-gray-700">
            <div class="">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6"></h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Hipotecas --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-purple-100 dark:border-gray-600">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-home class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">🏠 Hipotecas Residenciales</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Sistema Francés:</strong> Cuotas constantes por 15-30 años<br>
                            <strong>Ventaja:</strong> Presupuesto predecible
                        </p>
                    </div>

                    {{-- Préstamos Automotrices --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-blue-100 dark:border-gray-600">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-truck class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">🚗 Préstamos Automotrices</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Sistema Alemán:</strong> Cuotas decrecientes<br>
                            <strong>Ventaja:</strong> Menor costo total
                        </p>
                    </div>

                    {{-- Préstamos Empresariales --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-green-100 dark:border-gray-600">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <x-heroicon-o-building-office class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">🏢 Préstamos Empresariales</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Sistema Americano:</strong> Solo intereses + pago final<br>
                            <strong>Ventaja:</strong> Liquidez inicial
                        </p>
                    </div>
                </div>

                {{-- Consejos de Elección --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">🎯 ¿Cómo Elegir el Sistema Correcto?</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Elige Sistema Francés si:</h5>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Buscas estabilidad en tus pagos mensuales</li>
                                <li>• Tu ingreso es constante y predecible</li>
                                <li>• Prefieres facilidad para presupuestar</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Elige Sistema Alemán si:</h5>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Esperas que tus ingresos aumenten con el tiempo</li>
                                <li>• Quieres minimizar el costo total en intereses</li>
                                <li>• Puedes afrontar cuotas iniciales más altas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Calculadora --}}
        <x-sections.calculator id="calculadora">
            <x-slot:form>
                <x-sections.contents.calculator-form>
                    <x-forms.calculation-form calculation-type="amortizacion" />
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <x-slot:formula_slot>
                        <div class="space-y-3">
                            <div>
                                <p class="font-semibold text-blue-600 dark:text-blue-400">Sistema Francés:</p>
                                <p class="text-sm font-mono">Cuota = P × [r(1 + r)ⁿ] / [(1 + r)ⁿ - 1]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-green-600 dark:text-green-400">Sistema Alemán:</p>
                                <p class="text-sm font-mono">Amortización = P / n</p>
                                <p class="text-sm font-mono">Interés = Saldo × r</p>
                            </div>
                            <div>
                                <p class="font-semibold text-red-600 dark:text-red-400">Sistema Americano:</p>
                                <p class="text-sm font-mono">Cuota Periódica = P × r</p>
                                <p class="text-sm font-mono">Cuota Final = P</p>
                            </div>
                        </div>
                    </x-slot:formula_slot>
                    <x-slot:var_slot>
                        <div class="space-y-2">
                            <p><strong>P:</strong> Principal del préstamo</p>
                            <p><strong>r:</strong> Tasa de interés periódica (decimal)</p>
                            <p><strong>n:</strong> Número total de períodos</p>
                            <p><strong>Cuota:</strong> Pago total periódico</p>
                            <p><strong>Amortización:</strong> Reducción del capital</p>
                            <p><strong>Interés:</strong> Costo del préstamo por período</p>
                        </div>
                    </x-slot:var_slot>
                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>

        {{-- Consejos Finales --}}
        <x-sections.content title="💡 Consejos Financieros sobre Amortización" class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-yellow-200 dark:border-gray-700">
            <div class="">

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="text-yellow-500 text-xl mr-3">⭐</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Amortización Anticipada</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Realizar pagos adicionales al capital puede reducir significativamente el costo total en intereses y acortar el plazo del préstamo.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-yellow-500 text-xl mr-3">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Análisis de Escenarios</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Siempre simula diferentes plazos y tasas antes de comprometerte con un préstamo. Pequeñas diferencias en la tasa tienen gran impacto a largo plazo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="text-yellow-500 text-xl mr-3">🛡️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Protección contra Riesgos</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Considera seguros de desgravamen y de desempleo para proteger tu capacidad de pago ante imprevistos.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-yellow-500 text-xl mr-3">🎯</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Objetivos Claros</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Define si tu prioridad es tener la cuota más baja posible o minimizar el costo total. Esto determinará el sistema ideal para ti.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
