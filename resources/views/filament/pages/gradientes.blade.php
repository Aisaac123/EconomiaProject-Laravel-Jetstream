<x-filament-panels::page>
    <div class="space-y-6 min-h-[2500px]">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Sistemas de Gradientes"
            quote="“Los gradientes representan la realidad financiera: pocos flujos son constantes, la mayoría crecen o decrecen sistemáticamente.” — Anónimo"
            button-text="Explorar Calculadora"
            href="#calculadora"
        >
            <x-slot:icon>
                <x-heroicon-c-arrow-trending-up class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Introducción --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-purple-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">¿Qué son los Gradientes?</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Los <strong class="text-purple-600 dark:text-purple-400">gradientes</strong> son series de pagos o flujos de caja que
                            <strong>aumentan o disminuyen</strong> en cantidad constante o porcentual cada período.
                            Representan la realidad de la mayoría de los flujos financieros en proyectos de largo plazo.
                        </p>
                        <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">📊 Características Fundamentales</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                    <strong>No son constantes:</strong> Los flujos cambian sistemáticamente
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                    <strong>Patrón predecible:</strong> El cambio sigue una regla matemática
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                    <strong>Comunes en proyectos reales:</strong> Mantenimiento, salarios, ingresos
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">🎯 Aplicaciones Prácticas</h4>
                        <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-purple-500 mr-2">•</span>
                                <strong>Mantenimiento:</strong> Costos que aumentan con la antigüedad del equipo
                            </li>
                            <li class="flex items-start">
                                <span class="text-purple-500 mr-2">•</span>
                                <strong>Salarios:</strong> Aumentos escalonados por inflación o promociones
                            </li>
                            <li class="flex items-start">
                                <span class="text-purple-500 mr-2">•</span>
                                <strong>Ingresos:</strong> Crecimiento empresarial progresivo
                            </li>
                            <li class="flex items-start">
                                <span class="text-purple-500 mr-2">•</span>
                                <strong>Arriendos:</strong> Ajustes anuales por índice de precios
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- Comparación Visual Gradientes --}}
        <x-sections.content>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📈 Tipos de Gradientes</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Gradiente Aritmético --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-4">🔢 Gradiente Aritmético</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">Período 1</span>
                                <div class="w-16 h-4 bg-blue-300 rounded" title="$100"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">Período 2</span>
                                <div class="w-20 h-4 bg-blue-400 rounded" title="$120"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">Período 3</span>
                                <div class="w-24 h-4 bg-blue-500 rounded" title="$140"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">Período 4</span>
                                <div class="w-28 h-4 bg-blue-600 rounded" title="$160"></div>
                            </div>
                        </div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-3">
                            <strong>Característica:</strong> Aumento constante de $20 por período<br>
                            <strong>Fórmula:</strong> Aₜ = A₁ + (t-1)×G
                        </p>
                    </div>

                    {{-- Gradiente Geométrico --}}
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6">
                        <h4 class="font-semibold text-green-900 dark:text-green-100 mb-4">📊 Gradiente Geométrico</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-800 dark:text-green-200">Período 1</span>
                                <div class="w-16 h-4 bg-green-300 rounded" title="$100"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-800 dark:text-green-200">Período 2</span>
                                <div class="w-22 h-4 bg-green-400 rounded" title="$110"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-800 dark:text-green-200">Período 3</span>
                                <div class="w-28 h-4 bg-green-500 rounded" title="$121"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-800 dark:text-green-200">Período 4</span>
                                <div class="w-36 h-4 bg-green-600 rounded" title="$133.10"></div>
                            </div>
                        </div>
                        <p class="text-xs text-green-700 dark:text-green-300 mt-3">
                            <strong>Característica:</strong> Crecimiento del 10% por período<br>
                            <strong>Fórmula:</strong> Aₜ = A₁ × (1+g)ᵗ⁻¹
                        </p>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- GRADIENTE ARITMÉTICO DETALLADO --}}
        <x-sections.content>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">🔢 Gradiente Aritmético</h2>

            {{-- Introducción Gradiente Aritmético --}}
            <div class="mb-8">
                <x-filament::section heading="📝 Concepto y Características" collapsible="true" collapsed="false">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Definición</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    Serie de pagos que <strong>aumentan o disminuyen en una cantidad constante (G)</strong>
                                    cada período. También conocido como gradiente lineal o uniforme.
                                </p>
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Fórmula del Flujo</h5>
                                    <p class="text-blue-800 dark:text-blue-200 font-mono text-sm">
                                        Aₜ = A₁ + (t-1) × G
                                    </p>
                                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                                        <p><strong>Aₜ:</strong> Pago en el período t</p>
                                        <p><strong>A₁:</strong> Primer pago de la serie</p>
                                        <p><strong>G:</strong> Gradiente (incremento constante)</p>
                                        <p><strong>t:</strong> Período actual</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Tipos de Gradiente Aritmético</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                        <span class="text-green-500 mr-3">↑</span>
                                        <div>
                                            <p class="font-medium text-green-900 dark:text-green-100">Gradiente Creciente</p>
                                            <p class="text-xs text-green-700 dark:text-green-300">G > 0 (Valor positivo)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded">
                                        <span class="text-red-500 mr-3">↓</span>
                                        <div>
                                            <p class="font-medium text-red-900 dark:text-red-100">Gradiente Decreciente</p>
                                            <p class="text-xs text-red-700 dark:text-red-300">G < 0 (Valor negativo)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <span class="text-gray-500 mr-3">→</span>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Anualidad Constante</p>
                                            <p class="text-xs text-gray-700 dark:text-gray-300">G = 0 (Caso especial)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Valor Presente Gradiente Aritmético --}}
            <div class="mb-8">
                <x-filament::section heading="💰 Valor Presente (VP) - Vencido y Anticipado" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        {{-- Fórmulas VP --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">📅 GRADIENTE VENCIDO</h4>
                                <p class="text-blue-800 dark:text-blue-200 font-mono text-sm mb-4">
                                    VP = A₁ × [(1 - (1 + i)⁻ⁿ)/i] + G × [((1 + i)ⁿ - i × n - 1)/(i² × (1 + i)ⁿ)]
                                </p>
                                <div class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                    <p><strong>A₁:</strong> Primer pago</p>
                                    <p><strong>G:</strong> Gradiente aritmético</p>
                                    <p><strong>i:</strong> Tasa de interés periódica</p>
                                    <p><strong>n:</strong> Número de períodos</p>
                                </div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-3">⏩ GRADIENTE ANTICIPADO</h4>
                                <p class="text-purple-800 dark:text-purple-200 font-mono text-sm mb-4">
                                    VP = {A₁ × [(1 - (1 + i)⁻ⁿ)/i] + G × [((1 + i)ⁿ - i × n - 1)/(i² × (1 + i)ⁿ)]} × (1 + i)
                                </p>
                                <div class="text-xs text-purple-700 dark:text-purple-300">
                                    <p><strong>Nota:</strong> Se multiplica por (1 + i) para convertir de vencido a anticipado</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo VP Aritmético --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo: Mantenimiento con Aumento Lineal</h5>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                        <strong>Escenario:</strong> Mantenimiento anual que inicia en $1,000 y aumenta $200 cada año.
                                        Tasa: 10% anual, 5 años.
                                    </p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs">
                                            <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700">
                                                <th class="px-2 py-1 text-left">Año</th>
                                                <th class="px-2 py-1 text-right">Flujo</th>
                                                <th class="px-2 py-1 text-right">VP</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">1</td>
                                                <td class="px-2 py-1 text-right">$1,000</td>
                                                <td class="px-2 py-1 text-right">$909.09</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">2</td>
                                                <td class="px-2 py-1 text-right">$1,200</td>
                                                <td class="px-2 py-1 text-right">$991.74</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">3</td>
                                                <td class="px-2 py-1 text-right">$1,400</td>
                                                <td class="px-2 py-1 text-right">$1,052.14</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">4</td>
                                                <td class="px-2 py-1 text-right">$1,600</td>
                                                <td class="px-2 py-1 text-right">$1,092.82</td>
                                            </tr>
                                            <tr>
                                                <td class="px-2 py-1">5</td>
                                                <td class="px-2 py-1 text-right">$1,800</td>
                                                <td class="px-2 py-1 text-right">$1,117.21</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Cálculo con Fórmula:</h6>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">
                                        <strong>A₁ = $1,000, G = $200, i = 10%, n = 5</strong><br><br>
                                        VP = 1000×[(1-(1.1)⁻⁵)/0.1] + 200×[((1.1)⁵-0.1×5-1)/(0.1²×(1.1)⁵)]<br>
                                        VP = 1000×3.7908 + 200×6.8618 = $5,152.36
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Valor Futuro Gradiente Aritmético --}}
            <div class="mb-8">
                <x-filament::section heading="🚀 Valor Futuro (VF) - Vencido y Anticipado" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-3">📅 GRADIENTE VENCIDO</h4>
                                <p class="text-green-800 dark:text-green-200 font-mono text-sm">
                                    VF = A₁ × [((1 + i)ⁿ - 1)/i] + G × [((1 + i)ⁿ - i × n - 1)/i²]
                                </p>
                            </div>
                            <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-teal-900 dark:text-teal-100 mb-3">⏩ GRADIENTE ANTICIPADO</h4>
                                <p class="text-teal-800 dark:text-teal-200 font-mono text-sm">
                                    VF = {A₁ × [((1 + i)ⁿ - 1)/i] + G × [((1 + i)ⁿ - i × n - 1)/i²]} × (1 + i)
                                </p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </x-sections.content>

        {{-- GRADIENTE GEOMÉTRICO DETALLADO --}}
        <x-sections.content>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📊 Gradiente Geométrico</h2>

            {{-- Introducción Gradiente Geométrico --}}
            <div class="mb-8">
                <x-filament::section heading="📝 Concepto y Características" collapsible="true" collapsed="false">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Definición</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    Serie de pagos que <strong>aumentan o disminuyen en un porcentaje constante (g)</strong>
                                    cada período. También conocido como gradiente exponencial o porcentual.
                                </p>
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                    <h5 class="font-medium text-green-900 dark:text-green-100 mb-2">Fórmula del Flujo</h5>
                                    <p class="text-green-800 dark:text-green-200 font-mono text-sm">
                                        Aₜ = A₁ × (1 + g)ᵗ⁻¹
                                    </p>
                                    <div class="mt-2 text-xs text-green-700 dark:text-green-300">
                                        <p><strong>Aₜ:</strong> Pago en el período t</p>
                                        <p><strong>A₁:</strong> Primer pago de la serie</p>
                                        <p><strong>g:</strong> Tasa de crecimiento geométrico</p>
                                        <p><strong>t:</strong> Período actual</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-white mb-3">Casos Especiales Críticos</h4>
                                <div class="space-y-3">
                                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded border-l-4 border-yellow-500">
                                        <p class="font-medium text-yellow-900 dark:text-yellow-100">Caso 1: g = i</p>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-300">
                                            Tasa crecimiento = Tasa descuento<br>
                                            <strong>Fórmula especial:</strong> VP = A₁ × n / (1 + i)
                                        </p>
                                    </div>
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border-l-4 border-blue-500">
                                        <p class="font-medium text-blue-900 dark:text-blue-100">Caso 2: g < i</p>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">
                                            Crecimiento menor que descuento<br>
                                            <strong>Fórmula estándar aplicable</strong>
                                        </p>
                                    </div>
                                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded border-l-4 border-red-500">
                                        <p class="font-medium text-red-900 dark:text-red-100">Caso 3: g > i</p>
                                        <p class="text-xs text-red-700 dark:text-red-300">
                                            Crecimiento mayor que descuento<br>
                                            <strong>Fórmula estándar aplicable</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Valor Presente Gradiente Geométrico - CASOS g ≠ i --}}
            <div class="mb-8">
                <x-filament::section heading="💰 Valor Presente (VP) - Cuando g ≠ i" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        {{-- Fórmulas VP g ≠ i --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-green-900 dark:text-green-100 mb-3">📅 GRADIENTE VENCIDO (g ≠ i)</h4>
                                <p class="text-green-800 dark:text-green-200 font-mono text-sm mb-4">
                                    VP = A₁ × [(1 - (1 + g)ⁿ × (1 + i)⁻ⁿ)/(i - g)]
                                </p>
                                <div class="text-xs text-green-700 dark:text-green-300">
                                    <p><strong>Condición:</strong> g ≠ i (tasas diferentes)</p>
                                    <p><strong>Aplicación:</strong> Crecimiento diferente al descuento</p>
                                </div>
                            </div>
                            <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-teal-900 dark:text-teal-100 mb-3">⏩ GRADIENTE ANTICIPADO (g ≠ i)</h4>
                                <p class="text-teal-800 dark:text-teal-200 font-mono text-sm mb-4">
                                    VP = A₁ × [(1 - (1 + g)ⁿ × (1 + i)⁻ⁿ)/(i - g)] × (1 + i)
                                </p>
                                <div class="text-xs text-teal-700 dark:text-teal-300">
                                    <p><strong>Nota:</strong> Se multiplica por (1 + i) para anticipado</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ejemplo VP Geométrico g ≠ i --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo: Ingresos con Crecimiento del 8% anual (g < i)</h5>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                        <strong>Escenario:</strong> Ingresos que inician en $5,000 y crecen 8% anual.
                                        Tasa descuento: 12% anual, 4 años.
                                    </p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs">
                                            <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700">
                                                <th class="px-2 py-1 text-left">Año</th>
                                                <th class="px-2 py-1 text-right">Flujo</th>
                                                <th class="px-2 py-1 text-right">VP</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">1</td>
                                                <td class="px-2 py-1 text-right">$5,000</td>
                                                <td class="px-2 py-1 text-right">$4,464.29</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">2</td>
                                                <td class="px-2 py-1 text-right">$5,400</td>
                                                <td class="px-2 py-1 text-right">$4,304.25</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">3</td>
                                                <td class="px-2 py-1 text-right">$5,832</td>
                                                <td class="px-2 py-1 text-right">$4,150.47</td>
                                            </tr>
                                            <tr>
                                                <td class="px-2 py-1">4</td>
                                                <td class="px-2 py-1 text-right">$6,299</td>
                                                <td class="px-2 py-1 text-right">$4,002.69</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Cálculo con Fórmula:</h6>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">
                                        <strong>A₁ = $5,000, g = 8%, i = 12%, n = 4</strong><br><br>
                                        VP = 5000 × [(1 - (1.08)⁴ × (1.12)⁻⁴)/(0.12 - 0.08)]<br>
                                        VP = 5000 × [(1 - 1.3605 × 0.6355)/0.04]<br>
                                        VP = 5000 × 3.3921 = $16,960.50
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Valor Presente Gradiente Geométrico - CASO g = i --}}
            <div class="mb-8">
                <x-filament::section heading="⚖️ Valor Presente (VP) - Caso Especial g = i" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-6 border-l-4 border-yellow-500">
                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-3">🎯 CASO ESPECIAL: g = i</h4>
                            <p class="text-yellow-800 dark:text-yellow-200 font-mono text-sm mb-4">
                                VP = A₁ × n / (1 + i)
                            </p>
                            <div class="text-xs text-yellow-700 dark:text-yellow-300">
                                <p><strong>Condición crítica:</strong> Cuando la tasa de crecimiento (g) es igual a la tasa de descuento (i)</p>
                                <p><strong>Interpretación:</strong> El crecimiento compensa exactamente el descuento</p>
                                <p><strong>Resultado:</strong> VP equivalente a una anualidad constante descontada un período</p>
                            </div>
                        </div>

                        {{-- Ejemplo VP Geométrico g = i --}}
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Ejemplo: Flujos que crecen al 10% con descuento del 10% (g = i)</h5>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                        <strong>Escenario:</strong> Flujos que inician en $2,000 y crecen 10% anual.
                                        Tasa descuento: 10% anual, 3 años.
                                    </p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs">
                                            <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700">
                                                <th class="px-2 py-1 text-left">Año</th>
                                                <th class="px-2 py-1 text-right">Flujo</th>
                                                <th class="px-2 py-1 text-right">VP</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">1</td>
                                                <td class="px-2 py-1 text-right">$2,000</td>
                                                <td class="px-2 py-1 text-right">$1,818.18</td>
                                            </tr>
                                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                                <td class="px-2 py-1">2</td>
                                                <td class="px-2 py-1 text-right">$2,200</td>
                                                <td class="px-2 py-1 text-right">$1,818.18</td>
                                            </tr>
                                            <tr>
                                                <td class="px-2 py-1">3</td>
                                                <td class="px-2 py-1 text-right">$2,420</td>
                                                <td class="px-2 py-1 text-right">$1,818.18</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Cálculo con Fórmula Especial:</h6>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">
                                        <strong>A₁ = $2,000, g = 10%, i = 10%, n = 3</strong><br><br>
                                        VP = 2000 × 3 / (1 + 0.10)<br>
                                        VP = 6000 / 1.10 = $5,454.55<br><br>
                                        <strong>Nota:</strong> ¡Todos los VP son iguales! El crecimiento compensa exactamente el descuento.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            {{-- Valor Futuro Gradiente Geométrico --}}
            <div class="mb-8">
                <x-filament::section heading="🚀 Valor Futuro (VF) - Todos los Casos" collapsible="true" collapsed="true">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-3">📅 VF GRADIENTE VENCIDO</h4>
                                <p class="text-purple-800 dark:text-purple-200 font-mono text-sm mb-2">
                                    VF = A₁ × [(1 + i)ⁿ - (1 + g)ⁿ] / (i - g)
                                </p>
                                <p class="text-xs text-purple-700 dark:text-purple-300">
                                    <strong>Condición:</strong> g ≠ i
                                </p>
                            </div>
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-6">
                                <h4 class="font-semibold text-indigo-900 dark:text-indigo-100 mb-3">⏩ VF GRADIENTE ANTICIPADO</h4>
                                <p class="text-indigo-800 dark:text-indigo-200 font-mono text-sm mb-2">
                                    VF = {A₁ × [(1 + i)ⁿ - (1 + g)ⁿ] / (i - g)} × (1 + i)
                                </p>
                                <p class="text-xs text-indigo-700 dark:text-indigo-300">
                                    <strong>Condición:</strong> g ≠ i
                                </p>
                            </div>
                        </div>

                        {{-- Caso g = i para VF --}}
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-6">
                            <h4 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-3">🎯 VF CASO ESPECIAL: g = i</h4>
                            <p class="text-yellow-800 dark:text-yellow-200 font-mono text-sm">
                                VF = A₁ × n × (1 + i)ⁿ⁻¹
                            </p>
                            <div class="text-xs text-yellow-700 dark:text-yellow-300 mt-2">
                                <p><strong>Cuando g = i, el VF sigue una fórmula lineal multiplicada por el factor de capitalización</strong></p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </x-sections.content>

        {{-- COMPARATIVA Y APLICACIONES --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-orange-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📊 Comparativa: Aritmético vs Geométrico</h2>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    {{-- Tabla Comparativa --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Características Comparativas</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-3 py-2 text-left">Aspecto</th>
                                    <th class="px-3 py-2 text-left">Aritmético</th>
                                    <th class="px-3 py-2 text-left">Geométrico</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2 font-medium">Tipo de cambio</td>
                                    <td class="px-3 py-2">Constante ($)</td>
                                    <td class="px-3 py-2">Porcentual (%)</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2 font-medium">Crecimiento</td>
                                    <td class="px-3 py-2">Lineal</td>
                                    <td class="px-3 py-2">Exponencial</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2 font-medium">Fórmula flujo</td>
                                    <td class="px-3 py-2 font-mono text-xs">Aₜ = A₁ + (t-1)G</td>
                                    <td class="px-3 py-2 font-mono text-xs">Aₜ = A₁(1+g)ᵗ⁻¹</td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-3 py-2 font-medium">Aplicación típica</td>
                                    <td class="px-3 py-2">Costos fijos + inflación</td>
                                    <td class="px-3 py-2">Ingresos con crecimiento real</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 font-medium">Complejidad matemática</td>
                                    <td class="px-3 py-2">Media</td>
                                    <td class="px-3 py-2">Alta (casos especiales)</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Guía de Selección --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">🎯 ¿Cuándo Usar Cada Uno?</h4>
                        <div class="space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100">Usar Gradiente Aritmético cuando:</h5>
                                <ul class="text-xs text-blue-800 dark:text-blue-200 mt-2 space-y-1">
                                    <li>• Los aumentos son en montos fijos (ej: $100 anuales)</li>
                                    <li>• Los costos tienen componente fijo + variable</li>
                                    <li>• La inflación es baja y estable</li>
                                    <li>• Contratos con aumentos predeterminados</li>
                                </ul>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-green-900 dark:text-green-100">Usar Gradiente Geométrico cuando:</h5>
                                <ul class="text-xs text-green-800 dark:text-green-200 mt-2 space-y-1">
                                    <li>• Los cambios son porcentuales (ej: 5% anual)</li>
                                    <li>• Hay crecimiento real de ingresos</li>
                                    <li>• La inflación es significativa</li>
                                    <li>• Proyectos con escalabilidad</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Casos de Estudio Reales --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">💼 Casos de Estudio en la Vida Real</h4>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">🏢 Arriendos Comerciales</h5>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Aritmético:</strong> Aumentos fijos por contrato<br>
                                <strong>Geométrico:</strong> Ajustes por índice de precios
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">👥 Planes de Salarios</h5>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Aritmético:</strong> Aumentos por antigüedad<br>
                                <strong>Geométrico:</strong> Ajustes por inflación + mérito
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">🏭 Mantenimiento Industrial</h5>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Aritmético:</strong> Costos que aumentan linealmente<br>
                                <strong>Geométrico:</strong> Deterioro acelerado de equipos
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
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🧮 Calculadora de Gradientes</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center mb-6 py-8 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            🔧 <strong>Calculadora en Desarrollo</strong><br>
                            <span class="text-sm">Próximamente podrás simular gradientes aritméticos y geométricos</span>
                        </p>
                    </div>
                </x-sections.contents.calculator-form>
            </x-slot:form>

            <x-slot:explanation>
                <x-sections.contents.calculator-explanation>
                    <x-slot:formula_slot>
                        <div class="space-y-4">
                            <div>
                                <p class="font-semibold text-blue-600 dark:text-blue-400">Aritmético VP:</p>
                                <p class="text-xs font-mono">VP = A₁×[(1-(1+i)⁻ⁿ)/i] + G×[((1+i)ⁿ-i×n-1)/(i²×(1+i)ⁿ)]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-green-600 dark:text-green-400">Geométrico VP (g≠i):</p>
                                <p class="text-xs font-mono">VP = A₁×[(1-(1+g)ⁿ×(1+i)⁻ⁿ)/(i-g)]</p>
                            </div>
                            <div>
                                <p class="font-semibold text-yellow-600 dark:text-yellow-400">Geométrico VP (g=i):</p>
                                <p class="text-xs font-mono">VP = A₁×n/(1+i)</p>
                            </div>
                            <div>
                                <p class="font-semibold text-purple-600 dark:text-purple-400">Para anticipado:</p>
                                <p class="text-xs font-mono">Multiplicar por (1+i)</p>
                            </div>
                        </div>
                    </x-slot:formula_slot>
                    <x-slot:var_slot>
                        <div class="space-y-2 text-sm">
                            <p><strong>A₁:</strong> Primer pago</p>
                            <p><strong>G:</strong> Gradiente aritmético ($)</p>
                            <p><strong>g:</strong> Tasa crecimiento geométrico</p>
                            <p><strong>i:</strong> Tasa de descuento</p>
                            <p><strong>n:</strong> Número de períodos</p>
                            <p><strong>VP:</strong> Valor presente</p>
                            <p><strong>VF:</strong> Valor futuro</p>
                        </div>
                    </x-slot:var_slot>
                </x-sections.contents.calculator-explanation>
            </x-slot:explanation>
        </x-sections.calculator>

        {{-- CONSIDERACIONES FINALES --}}
        <x-sections.content>
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-indigo-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">💎 Consideraciones Finales sobre Gradientes</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">⚠️</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Caso g = i es Crítico</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Cuando la tasa de crecimiento iguala la tasa de descuento, se debe usar la fórmula especial.
                                    Error común: aplicar la fórmula estándar que genera división por cero.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">📅</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Período Cero en Anticipado</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    En gradientes anticipados, el primer pago ocurre en el período cero.
                                    Esto afecta tanto el valor presente como las fórmulas de cálculo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">🔍</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Análisis de Sensibilidad</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Los gradientes geométricos son muy sensibles a cambios en 'g'.
                                    Pequeñas variaciones en la tasa de crecimiento tienen gran impacto en el VP.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <span class="text-indigo-500 text-xl mr-3">🎯</span>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Aplicación Práctica</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    En la práctica, muchos flujos son mixtos: parte aritmética (costos fijos)
                                    y parte geométrica (componente inflacionario).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Resumen Fórmulas --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">📚 Resumen de Fórmulas Clave</h4>
                    <div class="grid md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Gradiente Aritmético</h5>
                            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                <li class="font-mono text-xs">VP = A₁×P + G×Q</li>
                                <li class="font-mono text-xs">P = (1-(1+i)⁻ⁿ)/i</li>
                                <li class="font-mono text-xs">Q = ((1+i)ⁿ-i×n-1)/(i²×(1+i)ⁿ)</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900 dark:text-white mb-2">Gradiente Geométrico</h5>
                            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                <li class="font-mono text-xs">g≠i: VP = A₁×[1-((1+g)/(1+i))ⁿ]/(i-g)</li>
                                <li class="font-mono text-xs">g=i: VP = A₁×n/(1+i)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>
    </div>

    {{-- Modales --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
