<x-filament-panels::page>
    <!-- Hero Section Mejorada -->
    <x-sections.heading-title
        title="Calculadora Financiera Académica"
        button-text="Comenzar"
        href="#conceptos"
    >
        <x-slot:quote>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">Herramienta educativa para el análisis de modelos financieros avanzados y tasas de interés.</p>
        </x-slot:quote>
        <x-slot:icon>
            <x-heroicon-c-calculator class="size-16 text-white" aria-hidden="true" />
        </x-slot:icon>
    </x-sections.heading-title>

    <!-- Temas expuestos -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 -mt-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex justify-center mb-6">
            Temas Fundamentales
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Fundamentos Básicos -->
            <div class="text-center p-4 rounded-lg transition-colors">
                <div class="mx-auto bg-primary-100 dark:bg-primary-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Tasa de Interés</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Conceptos y cálculos fundamentales</p>
            </div>

            <div class="text-center p-4 rounded-lg transition-colors">
                <div class="mx-auto bg-success-100 dark:bg-success-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Interés Simple</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Fórmulas y aplicaciones prácticas</p>
            </div>

            <div class="text-center p-4 rounded-lg transition-colors">
                <div class="mx-auto bg-warning-100 dark:bg-warning-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-warning-600 dark:text-warning-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Interés Compuesto</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">El poder del crecimiento exponencial</p>
            </div>

            <div class="text-center p-4 rounded-lg transition-colors">
                <div class="mx-auto bg-info-100 dark:bg-info-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-info-600 dark:text-info-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Anualidades</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Pagos periódicos y su valor temporal</p>
            </div>
        </div>

        <!-- Separador para temas avanzados -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white text-center mb-6">
                Temas Avanzados
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Sistemas Gradientes -->
                <div class="text-center p-4 rounded-lg transition-colors bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20">
                    <div class="mx-auto bg-purple-100 dark:bg-purple-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Sistemas Gradientes</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Series con incrementos constantes o porcentuales</p>
                </div>

                <!-- Sistemas de Amortización -->
                <div class="text-center p-4 rounded-lg transition-colors bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                    <div class="mx-auto bg-green-100 dark:bg-green-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Sistemas de Amortización</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Tablas de pago y distribución de intereses</p>
                </div>

                <!-- Sistemas de Capitalización -->
                <div class="text-center p-4 rounded-lg transition-colors bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20">
                    <div class="mx-auto bg-orange-100 dark:bg-orange-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Sistemas de Capitalización</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Acumulación y reinversión de intereses</p>
                </div>

                <!-- TIR -->
                <div class="text-center p-4 rounded-lg transition-colors bg-gradient-to-br from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20">
                    <div class="mx-auto bg-pink-100 dark:bg-pink-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-pink-600 dark:text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">TIR</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Tasa Interna de Retorno y evaluación de proyectos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de contenido principal -->
    <div id="conceptos" class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <div class="lg:col-span-2">
            <!-- Sección de metodología -->
            <x-filament::section>
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
                        <li class="text-lg">Analiza los resultados y tablas de amortización generadas</li>
                    </ol>
                </x-filament::card>
            </x-filament::section>

            <!-- Sección de modelos financieros con toggle interactivo -->
            <div x-data="modelToggle()" class="mt-6">
                <style>
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    @keyframes slideOut {
                        from {
                            opacity: 1;
                            transform: translateY(0);
                        }
                        to {
                            opacity: 0;
                            transform: translateY(-20px);
                        }
                    }

                    @keyframes scaleIn {
                        from {
                            transform: scale(0.95);
                            opacity: 0;
                        }
                        to {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    .toggle-container {
                        animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .toggle-bg {
                        transition: all 1.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .toggle-overlay {
                        transition: all 1.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .toggle-btn {
                        position: relative;
                        transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .emoji-icon {
                        display: inline-block;
                        transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .btn-text {
                        transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .toggle-btn.active .emoji-icon {
                        transform: scale(1.3) rotate(0deg);
                    }

                    .toggle-btn:not(.active) .emoji-icon {
                        transform: scale(0.8) rotate(-15deg);
                    }

                    .content-enter {
                        animation: slideIn 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                    }

                    .content-exit {
                        animation: slideOut 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                    }

                    .desc-text {
                        transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }

                    .difficulty-badge {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 6px 12px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    }

                    .difficulty-basic {
                        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
                        color: #0c4a6e;
                    }

                    .difficulty-intermediate {
                        background: linear-gradient(135deg, #fed7aa, #fcd34d);
                        color: #78350f;
                    }

                    .difficulty-advanced {
                        background: linear-gradient(135deg, #d8b4fe, #c084fc);
                        color: #5b21b6;
                    }

                    .difficulty-dot {
                        display: inline-flex;
                        gap: 3px;
                    }

                    .difficulty-dot span {
                        width: 5px;
                        height: 5px;
                        border-radius: 50%;
                        display: inline-block;
                        transition: all 0.3s ease;
                    }

                    [x-cloak] { display: none; }
                </style>

                <x-filament::section>
                    <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex items-center justify-between">
            <span class="flex items-center">
                <span class="mr-3 text-3xl transition-all duration-700" x-text="showFundamental ? '📚' : '🚀'"></span>
                <span class="transition-all duration-700" x-text="showFundamental ? 'Modelos Financieros Fundamentales' : 'Modelos Financieros Avanzados'"></span>
            </span>
                    </x-slot>

                    <!-- Toggle con animaciones fluidas -->
                    <div class="flex justify-center mb-4 toggle-container">
                        <div class="relative inline-flex bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-full p-2 shadow-2xl border border-gray-300 dark:border-gray-600 overflow-hidden">
                            <!-- Fondo animado -->
                            <div
                                class="toggle-bg absolute inset-y-2 w-1/2 rounded-full"
                                :class="[
                        showFundamental
                            ? 'from-primary-400 via-primary-500 to-blue-600 left-2'
                            : 'from-purple-400 via-purple-500 to-pink-600 right-2 left-auto'
                    ]"
                                :style="showFundamental ? 'background: linear-gradient(135deg, #60a5fa, #3b82f6, #2563eb)' : 'background: linear-gradient(135deg, #c084fc, #a855f7, #ec4899)'"
                            ></div>

                            <!-- Brillo interno -->
                            <div
                                class="toggle-overlay absolute inset-y-2 w-1/2 rounded-full opacity-40"
                                :class="showFundamental ? 'left-2' : 'right-2 left-auto'"
                                style="background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0), rgba(255,255,255,0.1));"
                            ></div>

                            <!-- Botón Fundamentales -->
                            <button
                                @click="showFundamental = true"
                                class="toggle-btn relative px-7 py-3 rounded-full font-bold text-sm z-10 flex items-center gap-3 w-auto transition-colors"
                                :class="[
                        showFundamental
                            ? 'text-white active'
                            : 'text-gray-600 dark:text-gray-400'
                    ]"
                            >
                                <span class="emoji-icon text-xl">📚</span>
                                <span class="btn-text">Fundamentales</span>
                            </button>

                            <!-- Botón Avanzados -->
                            <button
                                @click="showFundamental = false"
                                class="toggle-btn relative px-7 py-3 rounded-full font-bold text-sm z-10 flex items-center gap-3 w-auto transition-colors"
                                :class="[
                        !showFundamental
                            ? 'text-white active'
                            : 'text-gray-600 dark:text-gray-400'
                    ]"
                            >
                                <span class="emoji-icon text-xl">🚀</span>
                                <span class="btn-text">Avanzados</span>
                            </button>
                        </div>
                    </div>

                    <!-- Descripción con transición -->
                    <div class="relative h-12 mb-4 flex items-center justify-center">
                        <p class="desc-text text-gray-700 dark:text-gray-300 text-center absolute inset-0 flex items-center justify-center" x-show="showFundamental" :class="showFundamental ? 'content-enter' : 'content-exit'">
                            Explora los principales modelos de cálculo financiero utilizados en el ámbito académico y profesional.
                        </p>
                        <p class="desc-text text-gray-700 dark:text-gray-300 text-center absolute inset-0 flex items-center justify-center" x-show="!showFundamental" :class="!showFundamental ? 'content-enter' : 'content-exit'" style="display: none;">
                            Herramientas especializadas para análisis financieros complejos y evaluación de proyectos.
                        </p>
                    </div>

                    <!-- Grid Fundamentales -->
                    <div x-show="showFundamental" :class="showFundamental ? 'content-enter' : 'content-exit'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tarjeta Tasa de Interés -->
                            <a href="{{ url('/tasa-interes') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-primary-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-primary-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-primary-400 group-hover:via-primary-600 group-hover:to-primary-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-primary-500 group-hover:text-white transition-colors duration-500">💰</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Tasa de Interés</h3>
                                            <p class="mt-2 text-sm">
                                                Calcula el porcentaje aplicado al capital para determinar el interés en un período específico.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-basic">
                                <span>🎓</span>
                                <span>Básico</span>
                                <span class="difficulty-dot">
                                    <span style="background: #0c4a6e;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta Interés Simple -->
                            <a href="{{ url('/interes-simple') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-success-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-success-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-success-400 group-hover:via-success-600 group-hover:to-success-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-success-100 dark:bg-success-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-success-500 group-hover:text-white transition-colors duration-500">📈</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Interés Simple</h3>
                                            <p class="mt-2 text-sm">
                                                Calcula intereses exclusivamente sobre el capital inicial durante todo el período.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-basic">
                                <span>🎓</span>
                                <span>Básico</span>
                                <span class="difficulty-dot">
                                    <span style="background: #0c4a6e;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta Interés Compuesto -->
                            <a href="{{ url('/interes-compuesto') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-danger-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-danger-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-danger-400 group-hover:via-danger-600 group-hover:to-danger-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-danger-100 dark:bg-danger-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-danger-500 group-hover:text-white transition-colors duration-500">📊</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Interés Compuesto</h3>
                                            <p class="mt-2 text-sm">
                                                Calcula "intereses sobre intereses" para entender el crecimiento exponencial.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-intermediate">
                                <span>🎓</span>
                                <span>Intermedio</span>
                                <span class="difficulty-dot">
                                    <span style="background: #78350f;"></span>
                                    <span style="background: #78350f;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta Anualidad -->
                            <a href="{{ url('/anualidad') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-warning-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-warning-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-warning-400 group-hover:via-warning-600 group-hover:to-warning-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-warning-100 dark:bg-warning-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-warning-500 group-hover:text-white transition-colors duration-500">🔄</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Anualidad</h3>
                                            <p class="mt-2 text-sm">
                                                Serie de pagos o cobros iguales realizados a intervalos regulares.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-intermediate">
                                <span>🎓</span>
                                <span>Intermedio</span>
                                <span class="difficulty-dot">
                                    <span style="background: #78350f;"></span>
                                    <span style="background: #78350f;"></span>
                                    <span style="background: #cbd5e1;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Grid Avanzados -->
                    <div x-show="!showFundamental" :class="!showFundamental ? 'content-enter' : 'content-exit'" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tarjeta Sistemas Gradientes -->
                            <a href="{{ url('/gradientes') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-purple-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-purple-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-purple-400 group-hover:via-purple-600 group-hover:to-purple-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-purple-500 group-hover:text-white transition-colors duration-500">📐</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Sistemas Gradientes</h3>
                                            <p class="mt-2 text-sm">
                                                Series de pagos con incrementos constantes (aritméticos) o porcentuales (geométricos).
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                                        <span class="difficulty-badge difficulty-advanced mx-auto items-center">
                                            <span>🎓</span>
                                            <span>Avanzado</span>
                                            <span class="difficulty-dot">
                                                <span style="background: #5b21b6;"></span>
                                                <span style="background: #5b21b6;"></span>
                                                <span style="background: #5b21b6;"></span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta Sistemas de Amortización -->
                            <a href="{{ url('/amortizacion') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-green-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-green-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-green-400 group-hover:via-green-600 group-hover:to-green-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-green-500 group-hover:text-white transition-colors duration-500">📋</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Sistemas de Amortización</h3>
                                            <p class="mt-2 text-sm">
                                                Tablas de amortización para préstamos con diferentes sistemas de pago.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                                        <span class="difficulty-badge difficulty-advanced">
                                            <span>🎓</span>
                                            <span>Avanzado</span>
                                            <span class="difficulty-dot">
                                                <span style="background: #5b21b6;"></span>
                                                <span style="background: #5b21b6;"></span>
                                                <span style="background: #5b21b6;"></span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta Sistemas de Capitalización -->
                            <a href="{{ url('/capitalizacion') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-orange-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-orange-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-orange-400 group-hover:via-orange-600 group-hover:to-orange-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-orange-500 group-hover:text-white transition-colors duration-500">💎</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Sistemas de Capitalización</h3>
                                            <p class="mt-2 text-sm">
                                                Estudio de la acumulación y reinversión de intereses en diferentes períodos.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-advanced">
                                <span>🎓</span>
                                <span>Avanzado</span>
                                <span class="difficulty-dot">
                                    <span style="background: #5b21b6;"></span>
                                    <span style="background: #5b21b6;"></span>
                                    <span style="background: #5b21b6;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>

                            <!-- Tarjeta TIR -->
                            <a href="{{ url('/tir') }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-pink-500 p-5 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-pink-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-pink-400 group-hover:via-pink-600 group-hover:to-pink-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-pink-100 dark:bg-pink-900/30 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-3xl text-pink-500 group-hover:text-white transition-colors duration-500">🎯</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold">Tasa Interna de Retorno</h3>
                                            <p class="mt-2 text-sm">
                                                Evaluación de proyectos de inversión mediante el cálculo de la TIR.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                            <span class="difficulty-badge difficulty-advanced">
                                <span>🎓</span>
                                <span>Avanzado</span>
                                <span class="difficulty-dot">
                                    <span style="background: #5b21b6;"></span>
                                    <span style="background: #5b21b6;"></span>
                                    <span style="background: #5b21b6;"></span>
                                </span>
                            </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            @push('scripts')
                <script>
                    function modelToggle() {
                        return {
                            showFundamental: true
                        }
                    }
                </script>
            @endpush
        </div>

        <!-- Sidebar con información adicional -->
        <div class="space-y-6">
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
                        <p class="text-sm text-gray-600 dark:text-gray-400">En gradientes, identifica si es aritmético (incremento constante) o geométrico (incremento porcentual).</p>
                    </div>
                </div>
            </x-filament::section>

            <!-- Enlaces útiles -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-2">🔗</span> Enlaces de Interés
                </x-slot>

                <div class="space-y-2">
                    <a target="_blank" rel="noopener noreferrer" href="https://www.centro-virtual.com/recursos/glosarios/Matematicas_financieras.pdf" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Glosario de términos financieros
                    </a>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.rankia.cl/blog/analisis-ipsa/3513617-matematicas-financieras-definicion-formulas-ejemplos-aplicadas-inversion" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Fórmulas financieras esenciales
                    </a>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.superprof.es/apuntes/escolar/matematicas/aritmetica/proporcionalidad/ejercicios-y-problemas-resueltos-sobre-el-interes-compuesto.html" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Ejercicios prácticos resueltos
                    </a>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.gestiopolis.com/calculadora-tir-tasa-interna-retorno/" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Guía para cálculo de TIR
                    </a>
                </div>
            </x-filament::section>

            <!-- Calculadora rápida -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-success-600 dark:text-success-400 flex items-center">
                    <span class="mr-2">💼</span> Casos de Uso
                </x-slot>

                <div class="space-y-4">
                    <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🏦</span>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Préstamos Bancarios</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Calcula pagos mensuales y intereses totales.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-success-50 dark:bg-success-900/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">📈</span>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Inversiones</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Proyecta el crecimiento de tu capital.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-warning-50 dark:bg-warning-900/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🏠</span>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Hipotecas</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Planifica la compra de tu vivienda.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Evaluación de Proyectos</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Analiza viabilidad con TIR y VAN.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::section>

            <!-- Niveles de complejidad -->
            <x-filament::section>
                <x-slot name="heading" class="text-xl font-bold text-purple-600 dark:text-purple-400 flex items-center">
                    <span class="mr-2">🎓</span> Niveles de Aprendizaje
                </x-slot>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Básico</span>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Intermedio</span>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Avanzado</span>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        </div>
                    </div>
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
