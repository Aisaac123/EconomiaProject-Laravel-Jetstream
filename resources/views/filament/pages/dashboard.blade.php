<x-filament-panels::page>
    <!-- Hero Section Mejorada -->
    <div class="relative bg-gradient-to-r from-primary-500 via-primary-800 to-primary-800 dark:from-primary-700 dark:via-primary-800 dark:to-primary-900 rounded-2xl p-8 mb-10 text-center shadow-xl overflow-hidden">
        <div class="absolute inset-0 bg-grid-white/10 bg-[size:40px_40px]"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6">
                <span class="text-4xl">📊</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Calculadora Financiera Académica
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">
                Herramienta educativa avanzada para el análisis de modelos financieros y tasas de interés
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="#conceptos">
                    <button class="bg-white text-primary-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        Comenzar a calcular
                    </button>
                </a>
            </div>
        </div>
    </div>

    <!-- Temas expuestos -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex justify-center">
            Temas expuestos
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <div class="mx-auto bg-primary-100 dark:bg-primary-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Tasa de Interés</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Conceptos y cálculos fundamentales</p>
            </div>

            <div class="text-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <div class="mx-auto bg-success-100 dark:bg-success-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Interés Simple</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Fórmulas y aplicaciones prácticas</p>
            </div>

            <div class="text-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <div class="mx-auto bg-warning-100 dark:bg-warning-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-warning-600 dark:text-warning-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Interés Compuesto</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">El poder del crecimiento exponencial</p>
            </div>

            <div class="text-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <div class="mx-auto bg-info-100 dark:bg-info-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-info-600 dark:text-info-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Anualidades</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Pagos periódicos y su valor temporal</p>
            </div>
        </div>
    </div>

    <!-- Panel de contenido principal -->
    <div id="conceptos" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sección de conceptos financieros -->
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-3">📚</span> Conceptos Financieros Fundamentales
                </x-slot>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Explora los principales modelos de cálculo financiero utilizados en el ámbito académico y profesional.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Tarjeta Tasa de Interés -->
                    <a href="{{ url('/tasa-interes') }}" class="group block focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                        <x-filament::card class="rounded-xl border-l-4 border-primary-500 shadow-md group-hover:shadow-lg p-5">
                            <div class="flex items-start">
                                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg mr-4">
                                    <span class="text-2xl text-primary-500">💰</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Tasa de Interés</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                                        Calcula el porcentaje aplicado al capital para determinar el interés en un período específico.
                                    </p>
                                </div>
                            </div>
                        </x-filament::card>
                    </a>

                    <!-- Tarjeta Interés Simple -->
                    <a href="{{ url('/interes-simple') }}" class="group block focus:outline-none focus:ring-2 focus:ring-success-500 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                        <x-filament::card class="rounded-xl border-l-4 border-success-500 shadow-md group-hover:shadow-lg p-5">
                            <div class="flex items-start">
                                <div class="bg-success-100 dark:bg-success-900/30 p-3 rounded-lg mr-4">
                                    <span class="text-2xl text-success-500">📈</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Interés Simple</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                                        Calcula intereses exclusivamente sobre el capital inicial durante todo el período.
                                    </p>
                                </div>
                            </div>
                        </x-filament::card>
                    </a>

                    <!-- Tarjeta Interés Compuesto -->
                    <a href="{{ url('/interes-compuesto') }}" class="group block focus:outline-none focus:ring-2 focus:ring-danger-500 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                        <x-filament::card class="rounded-xl border-l-4 border-danger-500 shadow-md group-hover:shadow-lg p-5">
                            <div class="flex items-start">
                                <div class="bg-danger-100 dark:bg-danger-900/30 p-3 rounded-lg mr-4">
                                    <span class="text-2xl text-danger-500">📊</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Interés Compuesto</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                                        Calcula "intereses sobre intereses" para entender el crecimiento exponencial.
                                    </p>
                                </div>
                            </div>
                        </x-filament::card>
                    </a>

                    <!-- Tarjeta Anualidad -->
                    <a href="{{ url('/anualidad') }}" class="group block focus:outline-none focus:ring-2 focus:ring-warning-500 rounded-xl transition-all duration-300 transform hover:-translate-y-1">
                        <x-filament::card class="rounded-xl border-l-4 border-warning-500 shadow-md group-hover:shadow-lg p-5">
                            <div class="flex items-start">
                                <div class="bg-warning-100 dark:bg-warning-900/30 p-3 rounded-lg mr-4">
                                    <span class="text-2xl text-warning-500">🔄</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Anualidad</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                                        Serie de pagos o cobros iguales realizados a intervalos regulares.
                                    </p>
                                </div>
                            </div>
                        </x-filament::card>
                    </a>
                </div>
            </x-filament::section>

            <!-- Sección de metodología -->
            <x-filament::section class="mt-8">
                <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-3">🔍</span> Metodología de Cálculo
                </x-slot>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Nuestra herramienta utiliza fórmulas financieras estándar aceptadas académicamente para garantizar
                    precisión en todos los cálculos. Para cada modalidad, puedes ingresar los valores conocidos y el
                    sistema calculará automáticamente el parámetro faltante.
                </p>

                <x-filament::card class="rounded-xl border-2 border-primary-200 dark:border-primary-700 shadow-lg">
                    <h3 class="text-xl font-bold text-primary-600 dark:text-primary-400 mb-4 flex items-center">
                        <span class="text-2xl mr-2">💡</span> ¿Cómo utilizar esta herramienta?
                    </h3>
                    <ol class="text-gray-700 dark:text-gray-300 space-y-3 list-decimal list-inside pl-4">
                        <li class="text-lg">Selecciona el tipo de cálculo que deseas realizar</li>
                        <li class="text-lg">Ingresa los valores conocidos en los campos correspondientes</li>
                        <li class="text-lg">Nuestro sistema calculará automáticamente el valor faltante</li>
                    </ol>
                </x-filament::card>
            </x-filament::section>
        </div>

        <!-- Sidebar con información adicional -->
        <div class="space-y-8">
            <!-- Calculadora rápida -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-2">🧮</span> Calculadora Rápida
                </x-slot>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capital inicial</label>
                        <x-filament::input type="number" placeholder="$10,000" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tasa de interés (%)</label>
                        <x-filament::input type="number" placeholder="5%" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Período (años)</label>
                        <x-filament::input type="number" placeholder="5" />
                    </div>
                    <button class="w-full filament-button filament-button-size-md inline-flex items-center justify-center py-2 gap-2 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.5rem] px-4 text-sm text-white bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 focus:ring-white border-transparent">
                        <span class="mr-2">🧮</span> Calcular
                    </button>
                </div>
            </x-filament::section>

            <!-- Consejos útiles -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-2">💡</span> Consejos Prácticos
                </x-slot>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <span class="flex-shrink-0 text-success-500 text-lg mr-2">•</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Siempre verifica que la tasa de interés y el período estén en la misma unidad de tiempo.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 text-success-500 text-lg mr-2">•</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Para comparar opciones de inversión, convierte todas las tasas a la misma periodicidad.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 text-success-500 text-lg mr-2">•</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Considera el efecto de la inflación en tus cálculos para obtener resultados reales.</p>
                    </div>
                </div>
            </x-filament::section>

            <!-- Enlaces útiles -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-2">🔗</span> Enlaces de Interés
                </x-slot>

                <div class="space-y-2">
                    <a href="#" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Glosario de términos financieros
                    </a>
                    <a href="#" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Fórmulas financieras esenciales
                    </a>
                    <a href="#" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Ejercicios prácticos resueltos
                    </a>
                </div>
            </x-filament::section>
        </div>
    </div>

    <!-- Nota final -->
    <x-filament::section class="mt-8 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700 rounded-xl">
        <p class="text-center text-lg text-primary-700 dark:text-primary-300 font-medium">
            🎯 Esta herramienta ha sido desarrollada con fines educativos para apoyar el aprendizaje de conceptos financieros
            en el ámbito universitario. Siempre verifica tus cálculos con múltiples fuentes para aplicaciones del mundo real.
        </p>
    </x-filament::section>
</x-filament-panels::page>
