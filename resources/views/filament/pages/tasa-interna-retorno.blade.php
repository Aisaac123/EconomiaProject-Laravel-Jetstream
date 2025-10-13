<x-filament-panels::page>
    <div class="space-y-  min-h-[2800px]">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Tasa Interna de Retorno (TIR)"
            quote="“La TIR no es solo un número, es la brújula que guía las decisiones de inversión hacia la creación de valor verdadero.” — Anónimo"
            button-text="Explorar Calculadora"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-currency-dollar class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Introducción --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-amber-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">¿Qué es la Tasa Interna de Retorno (TIR)?</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            La <strong class="text-amber-600 dark:text-amber-400">Tasa Interna de Retorno (TIR)</strong> es la tasa de descuento que hace que el
                            <strong>Valor Presente Neto (VPN)</strong> de todos los flujos de caja de un proyecto sea igual a cero.
                            Representa la rentabilidad anualizada esperada de una inversión.
                        </p>
                        <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">🎯 Definición Matemática</h4>
                            <p class="text-amber-700 dark:text-amber-300 font-mono text-sm text-center">
                                0 = -Inversión + ∑ [FCₜ / (1 + TIR)ᵗ]
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 text-center mt-2">
                                Donde FCₜ es el flujo de caja en el período t
                            </p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">📊 Interpretación Práctica</h4>
                        <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <strong>TIR > Tasa Requerida:</strong> Proyecto viable (crea valor)
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">✗</span>
                                <strong>TIR < Tasa Requerida:</strong> Proyecto no viable (destruye valor)
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2">=</span>
                                <strong>TIR = Tasa Requerida:</strong> Proyecto indiferente (valor neutral)
                            </li>
                            <li class="flex items-start">
                                <span class="text-amber-500 mr-2">⚡</span>
                                <strong>TIR Múltiple:</strong> Puede haber más de una TIR en flujos no convencionales
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Conceptos Fundamentales --}}
        <x-sections.content>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">🎓 Fundamentos de la TIR</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Relación TIR-VPN --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-4">📈 Relación TIR vs VPN</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">Tasa Descuento</span>
                                <span class="text-sm text-blue-800 dark:text-blue-200">VPN</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-green-100 dark:bg-green-900/30 rounded">
                                <span class="text-sm">0%</span>
                                <span class="text-sm text-green-600 font-semibold">+$5,000</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-blue-100 dark:bg-blue-900/30 rounded">
                                <span class="text-sm">10%</span>
                                <span class="text-sm text-blue-600 font-semibold">+$2,500</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-amber-100 dark:bg-amber-900/30 rounded">
                                <span class="text-sm">18.5%</span>
                                <span class="text-sm text-amber-600 font-semibold">$0 (TIR)</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-red-100 dark:bg-red-900/30 rounded">
                                <span class="text-sm">25%</span>
                                <span class="text-sm text-red-600 font-semibold">-$1,000</span>
                            </div>
                        </div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-3">
                            La TIR es el punto donde la curva del VPN cruza el eje horizontal
                        </p>
                    </div>

                    {{-- Características Principales --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">⭐ Características Clave</h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Expresada en Porcentaje</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Fácil de comparar con tasas de referencia</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Considera el Valor del Dinero en el Tiempo</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Flujos futuros valen menos que flujos presentes</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-500 mr-2">•</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Mide Rentabilidad Relativa</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">No el valor absoluto creado</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="text-amber-500 mr-2">•</span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Supone Reinversión a la TIR</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Supuesto que puede no ser realista</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- MÉTODOS DE CÁLCULO --}}
        <x-sections.content>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">🧮 Métodos de Cálculo de la TIR</h2>

            {{-- Método Newton-Raphson --}}
            <div class="mb-8">
                <x-filament::section heading="⚡ Método Newton-Raphson (Analítico)" collapsible="true" collapsed="false">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Fundamento Matemático</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    Método iterativo que utiliza cálculo diferencial para encontrar raíces de ecuaciones.
                                    Converge rápidamente cuando la función es suave y la aproximación inicial es buena.
                                </p>
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Fórmula Iterativa</h5>
                                    <p class="text-blue-800 dark:text-blue-200 font-mono text-sm">
                                        TIRₙ₊₁ = TIRₙ - [VPN(TIRₙ) / VPN'(TIRₙ)]
                                    </p>
                                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                                        <p><strong>TIRₙ:</strong> Aproximación actual</p>
                                        <p><strong>VPN(TIRₙ):</strong> Valor Presente Neto en TIRₙ</p>
                                        <p><strong>VPN'(TIRₙ):</strong> Derivada del VPN en TIRₙ</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Ventajas y Desventajas</h4>
                                <div class="space-y-3">
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                        <p class="font-medium text-green-900 dark:text-green-100">✅ Ventajas</p>
                                        <ul class="text-xs text-green-700 dark:text-green-300 mt-1">
                                            <li>• Convergencia muy rápida (2-5 iteraciones)</li>
                                            <li>• Alta precisión (6-8 decimales)</li>
                                            <li>• Eficiente computacionalmente</li>
                                        </ul>
                                    </div>
                                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded">
                                        <p class="font-medium text-red-900 dark:text-red-100">❌ Desventajas</p>
                                        <ul class="text-xs text-red-700 dark:text-red-300 mt-1">
                                            <li>• Requiere cálculo de derivadas</li>
                                            <li>• Sensible a la aproximación inicial</li>
                                            <li>• Puede divergir en funciones complejas</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Newton-Raphson --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo: Inversión de $10,000 con flujos de $3,000, $4,000, $5,000, $6,000</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-left">Iteración</th>
                                        <th class="px-4 py-2 text-right">TIRₙ</th>
                                        <th class="px-4 py-2 text-right">VPN(TIRₙ)</th>
                                        <th class="px-4 py-2 text-right">VPN'(TIRₙ)</th>
                                        <th class="px-4 py-2 text-right">TIRₙ₊₁</th>
                                        <th class="px-4 py-2 text-right">Error</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">1</td>
                                        <td class="px-4 py-2 text-right">15.00%</td>
                                        <td class="px-4 py-2 text-right text-green-500">$487.25</td>
                                        <td class="px-4 py-2 text-right">-$18,456.32</td>
                                        <td class="px-4 py-2 text-right">17.64%</td>
                                        <td class="px-4 py-2 text-right">17.60%</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">2</td>
                                        <td class="px-4 py-2 text-right">17.64%</td>
                                        <td class="px-4 py-2 text-right text-green-500">$28.47</td>
                                        <td class="px-4 py-2 text-right">-$16,892.15</td>
                                        <td class="px-4 py-2 text-right">17.81%</td>
                                        <td class="px-4 py-2 text-right">0.96%</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">3</td>
                                        <td class="px-4 py-2 text-right">17.81%</td>
                                        <td class="px-4 py-2 text-right text-green-500">$0.12</td>
                                        <td class="px-4 py-2 text-right">-$16,801.34</td>
                                        <td class="px-4 py-2 text-right">17.81%</td>
                                        <td class="px-4 py-2 text-right">0.00%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-semibold">Resultado</td>
                                        <td class="px-4 py-2 text-right font-semibold text-amber-600">17.81%</td>
                                        <td class="px-4 py-2 text-right">$0.00</td>
                                        <td class="px-4 py-2 text-right">—</td>
                                        <td class="px-4 py-2 text-right">—</td>
                                        <td class="px-4 py-2 text-right">—</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Método de Interpolación Lineal --}}
            <div class="mb-8">
                <x-filament::section heading="📐 Método de Interpolación Lineal" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Fundamento del Método</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    Utiliza dos tasas de descuento que generan VPN con signos opuestos y
                                    asume una relación lineal entre ellas para estimar la TIR.
                                </p>
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                    <h5 class="font-medium text-green-900 dark:text-green-100 mb-2">Fórmula de Interpolación</h5>
                                    <p class="text-green-800 dark:text-green-200 font-mono text-sm">
                                        TIR ≈ i₁ + [(VPN₁ × (i₂ - i₁)) / (VPN₁ - VPN₂)]
                                    </p>
                                    <div class="mt-2 text-xs text-green-700 dark:text-green-300">
                                        <p><strong>i₁:</strong> Tasa con VPN positivo</p>
                                        <p><strong>i₂:</strong> Tasa con VPN negativo</p>
                                        <p><strong>VPN₁:</strong> VPN positivo en i₁</p>
                                        <p><strong>VPN₂:</strong> VPN negativo en i₂</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Requisitos</h4>
                                <div class="space-y-3">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                                        <p class="font-medium text-blue-900 dark:text-blue-100">📋 Condiciones Necesarias</p>
                                        <ul class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                            <li>• VPN(i₁) > 0 y VPN(i₂) < 0</li>
                                            <li>• i₁ y i₂ deben estar cercanas a la TIR real</li>
                                            <li>• La función VPN debe ser continua y monótona</li>
                                            <li>• i₂ - i₁ ≤ 5% para buena precisión</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo Interpolación --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo: Mismo proyecto usando interpolación</h5>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm">
                                            <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700">
                                                <th class="px-3 py-2 text-left">Tasa</th>
                                                <th class="px-3 py-2 text-right">VPN</th>
                                                <th class="px-3 py-2 text-right">Signo</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-3 py-2">15%</td>
                                                <td class="px-3 py-2 text-right text-green-500">+$487.25</td>
                                                <td class="px-3 py-2 text-right">+</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2">20%</td>
                                                <td class="px-3 py-2 text-right text-red-500">-$790.12</td>
                                                <td class="px-3 py-2 text-right">-</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded">
                                        <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Cálculo:</h6>
                                        <p class="text-xs text-gray-700 dark:text-gray-300">
                                            TIR ≈ 15% + [(487.25 × (20% - 15%)) / (487.25 - (-790.12))]<br>
                                            TIR ≈ 15% + [(487.25 × 5%) / 1277.37]<br>
                                            TIR ≈ 15% + 1.91% = <strong>16.91%</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded">
                                    <h6 class="font-semibold text-amber-900 dark:text-amber-100 mb-2">💡 Observación</h6>
                                    <p class="text-xs text-amber-700 dark:text-amber-300">
                                        <strong>Interpolación: 16.91% vs Real: 17.81%</strong><br>
                                        El error del 0.9% se debe a que la función VPN no es perfectamente lineal.
                                        Se puede mejorar usando tasas más cercanas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Método de Prueba y Error --}}
            <div class="mb-8">
                <x-filament::section heading="🎯 Método de Prueba y Error Sistemático" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Enfoque Iterativo</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    Se prueban diferentes tasas de descuento sistemáticamente hasta encontrar
                                    dos tasas consecutivas donde el VPN cambia de signo, luego se refina la búsqueda.
                                </p>
                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                    <h5 class="font-medium text-purple-900 dark:text-purple-100 mb-2">Algoritmo Básico</h5>
                                    <ol class="text-xs text-purple-700 dark:text-purple-300 space-y-1">
                                        <li>1. Probar tasa inicial (ej: 0%)</li>
                                        <li>2. Incrementar tasa en pasos (ej: 5%)</li>
                                        <li>3. Identificar cambio de signo en VPN</li>
                                        <li>4. Reducir paso y repetir cerca del cero</li>
                                        <li>5. Continuar hasta precisión deseada</li>
                                    </ol>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Estrategias de Búsqueda</h4>
                                <div class="space-y-3">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded">
                                        <p class="font-medium text-blue-900 dark:text-blue-100">Búsqueda Binaria</p>
                                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                            Dividir el intervalo a la mitad en cada iteración.
                                            Muy eficiente: precisión de 0.1% en ~10 iteraciones.
                                        </p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                        <p class="font-medium text-green-900 dark:text-green-100">Búsqueda por Incrementos</p>
                                        <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                                            Incrementos fijos o variables. Simple pero puede ser lento
                                            para alta precisión.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </x-sections.content>

        {{-- CASOS ESPECIALES Y PROBLEMAS --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-red-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">⚠️ Casos Especiales y Problemas con la TIR</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- TIR Múltiple --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">🔄 TIR Múltiple</h4>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Ocurre cuando los flujos de caja cambian de signo más de una vez.
                                Según la regla de los signos de Descartes, puede haber tantas TIR
                                reales como cambios de signo en la serie de flujos.
                            </p>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded">
                                <h5 class="font-medium text-yellow-900 dark:text-yellow-100 mb-2">Ejemplo de Flujo No Convencional</h5>
                                <p class="text-xs text-yellow-700 dark:text-yellow-300">
                                    -$1000, +$5000, -$6000, +$2000<br>
                                    <strong>Cambios de signo:</strong> - → + → - → + (3 cambios)<br>
                                    <strong>TIR posibles:</strong> 0%, 28.4%, 400%
                                </p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded">
                                <p class="text-xs text-red-700 dark:text-red-300">
                                    <strong>Problema:</strong> ¿Cuál TIR usar para decidir?<br>
                                    <strong>Solución:</strong> Usar VPN con tasa razonable o TIR Modificada
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Sin TIR Real --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">❌ Sin TIR Real</h4>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Algunos flujos de caja no tienen TIR real (solución en números reales).
                                Esto ocurre cuando el VPN nunca cruza cero para ninguna tasa de descuento.
                            </p>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Ejemplo Sin TIR Real</h5>
                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                    -$1000, -$500, -$300, +$1000<br>
                                    <strong>Comportamiento:</strong> VPN siempre negativo<br>
                                    <strong>Interpretación:</strong> Proyecto nunca es viable
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded">
                                <p class="text-xs text-green-700 dark:text-green-300">
                                    <strong>Solución:</strong> Analizar VPN con diferentes tasas y
                                    considerar que el proyecto puede no ser viable en ningún escenario.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TIR Modificada (TIRM) --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">🔄 TIR Modificada (TIRM)</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                La <strong>TIR Modificada</strong> resuelve el problema del supuesto de reinversión
                                a la TIR usando tasas de reinversión y financiamiento más realistas.
                            </p>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded">
                                <h5 class="font-medium text-purple-900 dark:text-purple-100 mb-2">Fórmula TIRM</h5>
                                <p class="text-purple-800 dark:text-purple-200 font-mono text-sm">
                                    TIRM = [VF(flujos positivos) / VP(flujos negativos)]^(1/n) - 1
                                </p>
                                <div class="mt-2 text-xs text-purple-700 dark:text-purple-300">
                                    <p><strong>VF:</strong> Valor futuro a tasa de reinversión</p>
                                    <p><strong>VP:</strong> Valor presente a tasa de financiamiento</p>
                                    <p><strong>n:</strong> Vida del proyecto</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-3">Ventajas de la TIRM</h5>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Elimina el problema de TIR múltiple
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Supuestos de reinversión más realistas
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Siempre produce una única solución
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✓</span>
                                    Mejor para comparar proyectos mutuamente excluyentes
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- APLICACIONES Y EJEMPLOS PRÁCTICOS --}}
        <x-sections.content>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">💼 Aplicaciones Prácticas y Ejemplos</h2>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    {{-- Ejemplo Proyecto Simple --}}
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6">
                        <h4 class="font-semibold text-green-900 dark:text-green-100 mb-4">📊 Proyecto de Inversión Simple</h4>
                        <div class="space-y-4">
                            <div class="bg-white dark:bg-gray-700 p-4 rounded">
                                <h5 class="font-medium text-gray-900 dark:text-white mb-2">Flujos de Caja</h5>
                                <div class="text-sm">
                                    <p>Año 0: -$10,000 (Inversión)</p>
                                    <p>Año 1: +$3,000</p>
                                    <p>Año 2: +$4,000</p>
                                    <p>Año 3: +$5,000</p>
                                    <p>Año 4: +$6,000</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-2 rounded">
                                    <p class="font-medium">TIR</p>
                                    <p class="text-blue-600 font-semibold">17.81%</p>
                                </div>
                                <div class="bg-amber-50 dark:bg-amber-900/20 p-2 rounded">
                                    <p class="font-medium">Tasa Requerida</p>
                                    <p class="text-amber-600 font-semibold">12%</p>
                                </div>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded">
                                <p class="text-green-800 dark:text-green-200 text-sm text-center">
                                    <strong>DECISIÓN: ACEPTAR</strong><br>
                                    TIR (17.81%) > Tasa Requerida (12%)
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Comparación de Proyectos --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-4">⚖️ Comparación de Proyectos Mutuamente Excluyentes</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700">
                                    <th class="px-3 py-2 text-left">Proyecto</th>
                                    <th class="px-3 py-2 text-right">Inversión</th>
                                    <th class="px-3 py-2 text-right">TIR</th>
                                    <th class="px-3 py-2 text-right">VPN @12%</th>
                                    <th class="px-3 py-2 text-right">Decisión TIR</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2">A</td>
                                    <td class="px-3 py-2 text-right">$10,000</td>
                                    <td class="px-3 py-2 text-right text-green-500">22%</td>
                                    <td class="px-3 py-2 text-right">$2,500</td>
                                    <td class="px-3 py-2 text-right">Aceptar</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">B</td>
                                    <td class="px-3 py-2 text-right">$25,000</td>
                                    <td class="px-3 py-2 text-right text-green-500">18%</td>
                                    <td class="px-3 py-2 text-right">$4,800</td>
                                    <td class="px-3 py-2 text-right">Aceptar</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded">
                            <p class="text-yellow-800 dark:text-yellow-200 text-xs">
                                <strong>Conflicto TIR vs VPN:</strong> Proyecto A tiene mayor TIR (22% vs 18%)
                                pero Proyecto B crea más valor ($4,800 vs $2,500).
                                <strong>Solución:</strong> Usar VPN para maximizar valor.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Análisis de Sensibilidad --}}
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">📈 Análisis de Sensibilidad de la TIR</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-3">Escenarios de Flujos de Caja</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="px-3 py-2 text-left">Escenario</th>
                                        <th class="px-3 py-2 text-right">FC Año 1</th>
                                        <th class="px-3 py-2 text-right">FC Año 2</th>
                                        <th class="px-3 py-2 text-right">TIR</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-3 py-2">Pesimista</td>
                                        <td class="px-3 py-2 text-right">$2,500</td>
                                        <td class="px-3 py-2 text-right">$3,500</td>
                                        <td class="px-3 py-2 text-right text-red-500">8.5%</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-3 py-2">Más Probable</td>
                                        <td class="px-3 py-2 text-right">$3,000</td>
                                        <td class="px-3 py-2 text-right">$4,000</td>
                                        <td class="px-3 py-2 text-right text-amber-500">12.2%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2">Optimista</td>
                                        <td class="px-3 py-2 text-right">$3,500</td>
                                        <td class="px-3 py-2 text-right">$4,500</td>
                                        <td class="px-3 py-2 text-right text-green-500">15.8%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">💡 Interpretación del Análisis</h5>
                            <p class="text-xs text-gray-700 dark:text-gray-300">
                                <strong>Rango de TIR:</strong> 8.5% a 15.8%<br>
                                <strong>Tasa requerida:</strong> 10%<br>
                                <strong>Riesgo:</strong> En escenario pesimista, TIR está cerca de la tasa requerida<br>
                                <strong>Recomendación:</strong> Proyecto aceptable pero con riesgo moderado
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- CALCULADORA --}}
        <x-sections.calculator id="calculadora">
            <x-slot:form>
                <x-sections.contents.calculator-form>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🧮 Calculadora de TIR</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center mb-6 py-8 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            🔧 <strong>Calculadora en Desarrollo</strong><br>
                            <span class="text-sm">Próximamente podrás calcular TIR para tus flujos de caja</span>
                        </p>
                    </div>
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <x-slot:formula_slot>
                        <div class="space-y-3">
                            <div>
                                <p class="font-semibold text-amber-600 dark:text-amber-400">Ecuación Fundamental:</p>
                                <p class="text-xs font-mono">0 = -FC₀ + ∑ [FCₜ / (1 + TIR)ᵗ]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-600 dark:text-blue-400">Newton-Raphson:</p>
                                <p class="text-xs font-mono">TIRₙ₊₁ = TIRₙ - [VPN(TIRₙ) / VPN'(TIRₙ)]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-green-600 dark:text-green-400">Interpolación Lineal:</p>
                                <p class="text-xs font-mono">TIR ≈ i₁ + [(VPN₁ × (i₂ - i₁)) / (VPN₁ - VPN₂)]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-purple-600 dark:text-purple-400">TIR Modificada:</p>
                                <p class="text-xs font-mono">TIRM = [VF⁺ / VP⁻]^(1/n) - 1</p>
                            </div>
                        </div>
                    </x-slot:formula_slot>
                    <x-slot:var_slot>
                        <div class="space-y-2 text-sm">
                            <p><strong>TIR:</strong> Tasa Interna de Retorno</p>
                            <p><strong>FCₜ:</strong> Flujo de caja en período t</p>
                            <p><strong>VPN:</strong> Valor Presente Neto</p>
                            <p><strong>TIRM:</strong> TIR Modificada</p>
                            <p><strong>VF⁺:</strong> Valor futuro flujos positivos</p>
                            <p><strong>VP⁻:</strong> Valor presente flujos negativos</p>
                            <p><strong>n:</strong> Número de períodos</p>
                        </div>
                    </x-slot:var_slot>
                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>

        {{-- CONSIDERACIONES FINALES --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">💎 Consideraciones Finales sobre la TIR</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-blue-500 text-xl mr-3">🎯</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Cuando Usar TIR</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    • Proyectos independientes con flujos convencionales<br>
                                    • Comparación rápida con tasa de oportunidad<br>
                                    • Comunicación con no especialistas (fácil de entender)<br>
                                    • Análisis de rentabilidad porcentual
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-amber-500 text-xl mr-3">⚠️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Limitaciones Importantes</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    • Supuesto de reinversión a la TIR (poco realista)<br>
                                    • Problemas con TIR múltiple en flujos no convencionales<br>
                                    • Puede contradecir al VPN en proyectos excluyentes<br>
                                    • No considera el tamaño de la inversión
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-green-500 text-xl mr-3">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Mejores Prácticas</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    • Siempre calcular VPN junto con TIR<br>
                                    • Usar TIR modificada para supuestos realistas<br>
                                    • Realizar análisis de sensibilidad<br>
                                    • Considerar período de recuperación descontado<br>
                                    • Validar con múltiples métricas de rentabilidad
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">🚀</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">En el Mundo Real</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    La TIR es ampliamente utilizada en:<br>
                                    • Banca de inversión (private equity)<br>
                                    • Evaluación de proyectos corporativos<br>
                                    • Análisis de bienes raíces<br>
                                    • Fondos de capital de riesgo
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Resumen Decisiones --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Matriz de Decisiones con TIR</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-4 py-2 text-left">Situación</th>
                                <th class="px-4 py-2 text-left">TIR vs Tasa Requerida</th>
                                <th class="px-4 py-2 text-left">VPN</th>
                                <th class="px-4 py-2 text-left">Decisión</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">Proyecto Simple</td>
                                <td class="px-4 py-2">TIR > Tasa Requerida</td>
                                <td class="px-4 py-2">VPN > 0</td>
                                <td class="px-4 py-2 text-green-500 font-semibold">ACEPTAR</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">Proyecto Simple</td>
                                <td class="px-4 py-2">TIR < Tasa Requerida</td>
                                <td class="px-4 py-2">VPN < 0</td>
                                <td class="px-4 py-2 text-red-500 font-semibold">RECHAZAR</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">Proyectos Excluyentes</td>
                                <td class="px-4 py-2">TIR A > TIR B</td>
                                <td class="px-4 py-2">VPN B > VPN A</td>
                                <td class="px-4 py-2 text-blue-500 font-semibold">ELEGIR B (por VPN)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">TIR Múltiple</td>
                                <td class="px-4 py-2">Varias TIR > Tasa Req.</td>
                                <td class="px-4 py-2">—</td>
                                <td class="px-4 py-2 text-amber-500 font-semibold">USAR TIRM O VPN</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-sections.content>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
