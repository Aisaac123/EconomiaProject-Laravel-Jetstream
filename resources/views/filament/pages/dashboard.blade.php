<div class="container mx-auto my-8">
<!-- Encabezado principal -->
    <div class="border-2 border-primary-300 dark:border-primary-400 bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 text-center shadow-lg transform transition-all duration-300 hover:shadow-xl">
        <h1 class="text-4xl font-bold text-primary-700 dark:text-primary-200 mb-3">
            📊 Calculadora Financiera Académica
        </h1>
        <p class="text-xl text-gray-700 dark:text-gray-300">
            Herramienta educativa para el análisis de tasas de interés y modelos financieros
        </p>
    </div>

    <!-- Introducción -->
    <x-filament::section class="rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
        <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
            ¡Bienvenido a la plataforma de cálculo financiero! 🎓
        </x-slot>

        <p class="text-lg leading-7 mt-4 text-gray-700 dark:text-gray-300">
            Esta herramienta ha sido diseñada específicamente para apoyar el aprendizaje de conceptos financieros
            fundamentales en el ámbito universitario. Podrás explorar y calcular diferentes parámetros relacionados
            con tasas de interés, inversiones y anualidades de manera precisa y educativa.
        </p>
    </x-filament::section>

    <!-- Sección de conceptos -->
    <x-filament::section class="mt-8">
        <x-slot name="heading" class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
            📚 Conceptos Financieros Fundamentales
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Tarjeta Tasa de Interés -->
            <a href="{{ url('/tasa-interes') }}" class="block focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-xl">
                <x-filament::card class="rounded-xl border-l-4 border-primary-500 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <h3 class="text-xl font-bold text-primary-600 dark:text-primary-400 mb-3 flex items-center">
                        <span class="text-2xl mr-2">💰</span> Tasa de Interés
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-6">
                        Porcentaje que se aplica al capital para determinar el interés generado en un período de tiempo específico.
                        Es el costo de pedir dinero prestado o la ganancia por invertirlo.
                    </p>
                </x-filament::card>
            </a>
            <!-- Tarjeta Interés Simple -->
            <a href="{{ url('/interes-simple') }}" class="block focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-xl">
                <x-filament::card class="rounded-xl border-l-4 border-success-500 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <h3 class="text-xl font-bold text-success-600 dark:text-success-400 mb-3 flex items-center">
                        <span class="text-2xl mr-2">📈</span> Interés Simple
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-6">
                        Modelo de cálculo donde los intereses se calculan exclusivamente sobre el capital inicial durante
                        todo el período, sin considerar la capitalización de intereses.
                    </p>
                </x-filament::card>
            </a>

            <!-- Tarjeta Interés Compuesto -->
            <a href="{{ url('/interes-compuesto') }}" class="block focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-xl">
                <x-filament::card class="rounded-xl border-l-4 border-danger-500 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <h3 class="text-xl font-bold text-danger-600 dark:text-danger-400 mb-3 flex items-center">
                        <span class="text-2xl mr-2">📊</span> Interés Compuesto
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-6">
                        Los intereses generados se acumulan al capital inicial, generando así "intereses sobre intereses".
                        Este concepto es fundamental para entender el crecimiento exponencial de las inversiones.
                    </p>
                </x-filament::card>
            </a>

            <!-- Tarjeta Anualidad -->
            <a href="{{ url('/anualidad') }}" class="block focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-xl">
                <x-filament::card class="rounded-xl border-l-4 border-warning-500 shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <h3 class="text-xl font-bold text-warning-600 dark:text-warning-400 mb-3 flex items-center">
                        <span class="text-2xl mr-2">🔄</span> Anualidad
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-6">
                        Serie de pagos o cobros iguales realizados a intervalos regulares durante un período determinado.
                        Las anualidades son esenciales en el análisis de préstamos y planes de inversión.
                    </p>
                </x-filament::card>
            </a>

        </div>
    </x-filament::section>

    <!-- Sección de metodología con acento visual -->
    <x-filament::section class="mt-8 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl">
        <x-slot name="heading" class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            🔍 Metodología de Cálculo
        </x-slot>

        <p class="text-lg leading-7 text-gray-700 dark:text-gray-300 mb-6">
            Nuestra herramienta utiliza fórmulas financieras estándar aceptadas académicamente para garantizar
            precisión en todos los cálculos. Para cada modalidad, puedes ingresar los valores conocidos y el
            sistema calculará automáticamente el parámetro faltante.
        </p>

        <x-filament::card class="rounded-xl border-2 border-primary-200 dark:border-primary-700 shadow-lg mt-6">
            <h3 class="text-xl font-bold text-primary-600 dark:text-primary-400 mb-4 flex items-center">
                <span class="text-2xl mr-2">💡</span> ¿Cómo utilizar esta herramienta?
            </h3>
            <ol class="text-gray-700 dark:text-gray-300 space-y-3 list-decimal list-inside pl-4">
                <li class="text-lg">Selecciona el tipo de cálculo que deseas realizar</li>
                <li class="text-lg">Ingresa los valores conocidos en los campos correspondientes</li>
                <li class="text-lg">Nuestro sistema calculará automáticamente el valor faltante</li>
                <li class="text-lg">Analiza los resultados y utiliza las opciones de exportación si es necesario</li>
            </ol>
        </x-filament::card>
    </x-filament::section>

    <!-- Nota final con diseño destacado -->
    <x-filament::section class="mt-8 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700 rounded-xl p-6">
        <p class="text-center text-lg text-primary-700 dark:text-primary-300 font-medium">
            🎯 Esta herramienta ha sido desarrollada con fines educativos para apoyar el aprendizaje de conceptos financieros
            en el ámbito universitario. Siempre verifica tus cálculos con múltiples fuentes para aplicaciones del mundo real.
        </p>
    </x-filament::section>

</div>
