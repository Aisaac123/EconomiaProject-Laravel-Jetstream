<x-filament-panels::page>
    <!-- Hero Section Mejorada -->
    <x-sections.heading-title
        title="Calculadora Financiera Académica"
        button-text="Comenzar"
        href="#conceptos"
    >
        <x-slot:quote>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">Herramienta educativa para el análisis de modelos financieros básicos, avanzados y simulación de créditos.</p>
        </x-slot:quote>
        <x-slot:icon>
            <x-heroicon-c-calculator class="size-16 text-white" aria-hidden="true" />
        </x-slot:icon>
    </x-sections.heading-title>

    <!-- Temas expuestos - Agregando Simulador de Créditos -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 -mt-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex justify-center mb-6">
            Temas Fundamentales
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
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

            <!-- Nuevo: Simulador de Créditos -->
            <div class="text-center p-4 rounded-lg transition-colors bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20">
                <div class="mx-auto bg-blue-100 dark:bg-blue-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Simulador de Créditos</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">Cuatro modelos completos de simulación</p>
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
            <!-- Sección de metodología - Agregando referencia al simulador -->
            <x-filament::section>
                <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex items-center">
                    <span class="mr-3">🔍</span> Metodología de Cálculo
                </x-slot>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Nuestra herramienta utiliza fórmulas financieras estándar aceptadas académicamente para garantizar
                    precisión en todos los cálculos. Para cada modalidad, puedes ingresar los valores conocidos y el
                    sistema calculará automáticamente el parámetro faltante.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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

                    <x-filament::card class="rounded-xl border-2 border-blue-200 dark:border-blue-700 shadow-lg bg-blue-50 dark:bg-blue-900/20">
                        <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                            <span class="text-2xl mr-2">🚀</span> Simulador de Créditos
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">
                            Explora nuestro <strong>simulador completo de créditos</strong> con cuatro modelos diferentes:
                        </p>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-2 list-disc list-inside pl-4">
                            <li>Modelo de Gradientes</li>
                            <li>Interés Simple</li>
                            <li>Interés Compuesto</li>
                            <li>Sistemas de Amortización</li>
                        </ul>
                        <div class="mt-4 pt-4 flex justify-center">
                            <a href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-300">
                                <span class="mr-2">📊</span> Ir al Simulador
                            </a>
                        </div>
                    </x-filament::card>
                </div>
            </x-filament::section>

            <!-- Sección de modelos financieros con toggle interactivo MEJORADO -->
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
                        transition: left 0.25s ease, background-color 0.3s ease;
                    }

                    .toggle-bg {
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                    }

                    .dark .toggle-bg {
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.15);
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

                    /* 🔄 Animaciones más suaves y naturales */
                    .toggle-container {
                        transition: transform 0.3s ease, opacity 0.3s ease;
                        opacity: 1;
                    }

                    .toggle-bg {
                        transition: left 0.25s ease, background-color 0.25s ease, box-shadow 0.25s ease;
                        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
                    }
                    .dark .toggle-bg {
                        box-shadow: 0 0 8px rgba(255, 255, 255, 0.1);
                    }

                    /* Movimiento más sutil y sin rebote */
                    .toggle-btn {
                        position: relative;
                        transition: color 0.25s ease, transform 0.25s ease, opacity 0.25s ease;
                    }
                    .toggle-btn.active {
                        transform: scale(1);
                        opacity: 1;
                    }
                    .toggle-btn:not(.active) {
                        transform: scale(0.96);
                        opacity: 0.8;
                    }

                    /* Emojis más suaves */
                    .emoji-icon {
                        transition: transform 0.25s ease;
                    }
                    .toggle-btn.active .emoji-icon {
                        transform: scale(1.05);
                    }
                    .toggle-btn:not(.active) .emoji-icon {
                        transform: scale(0.9);
                    }

                    /* Overlay de brillo más elegante */
                    .toggle-overlay {
                        transition: left 0.3s ease, opacity 0.3s ease;
                        opacity: 0.3;
                    }

                    /* Descripción más fluida */
                    .desc-text {
                        transition: opacity 0.3s ease, transform 0.3s ease;
                    }
                    [x-show="true"] .desc-text {
                        opacity: 1;
                        transform: translateY(0);
                    }

                    [x-cloak] { display: none; }
                </style>

                <x-filament::section>
                    <x-slot name="heading" class="text-2xl font-bold text-primary-600 dark:text-primary-400 flex items-center justify-between">
                        <span class="flex items-center">
                            <span class="mr-3 text-3xl transition-all duration-700" x-text="activeTab === 'fundamental' ? '📚' : activeTab === 'advanced' ? '🚀' : '💳'"></span>
                            <span class="transition-all duration-700" x-text="activeTab === 'fundamental' ? 'Modelos Financieros Fundamentales' : activeTab === 'advanced' ? 'Modelos Financieros Avanzados' : 'Simulador de Créditos'"></span>
                        </span>
                    </x-slot>

                    <!-- 🔄 Nuevo toggle minimalista -->
                    <div class="flex justify-center mb-6 toggle-container">
                        <div class="relative flex bg-gray-50 dark:bg-gray-900 rounded-full p-2 shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden w-[480px] sm:w-[520px] transition-all duration-500 ease-out">
                            <!-- Fondo deslizante -->
                            <div
                                class="absolute top-0 bottom-0 w-1/3 rounded-full transition-all duration-500 ease-[cubic-bezier(0.45,0,0.2,1)]"
                                :class="{
            'left-0 bg-green-200 dark:bg-green-700/60': activeTab === 'fundamental',
            'left-1/3 bg-purple-300/80 dark:bg-purple-700/60': activeTab === 'advanced',
            'left-2/3 bg-blue-300/80 dark:bg-blue-700/60': activeTab === 'simulator'
        }"
                            ></div>

                            <!-- Botón: Fundamentales -->
                            <button
                                @click="activeTab = 'fundamental'"
                                class="toggle-btn relative z-10 flex-1 h-full flex items-center justify-center text-[15px] font-medium rounded-full transition-all duration-500 ease-[cubic-bezier(0.45,0,0.2,1)]"
                                :class="activeTab === 'fundamental' ? 'text-green-800 dark:text-green-200 active' : 'text-gray-700 dark:text-gray-300'"
                            >
                                <span class="emoji-icon text-lg mr-1">📚</span>
                                <span class="btn-text hidden sm:inline">Fundamentales</span>
                            </button>

                            <!-- Botón: Avanzados -->
                            <button
                                @click="activeTab = 'advanced'"
                                class="toggle-btn relative z-10 flex-1 h-full flex items-center justify-center text-[15px] font-medium rounded-full transition-all duration-500 ease-[cubic-bezier(0.45,0,0.2,1)]"
                                :class="activeTab === 'advanced' ? 'text-purple-800 dark:text-purple-200 active' : 'text-gray-700 dark:text-gray-300'"
                            >
                                <span class="emoji-icon text-lg mr-1">🚀</span>
                                <span class="btn-text hidden sm:inline">Avanzados</span>
                            </button>

                            <!-- Botón: Simulador -->
                            <button
                                @click="activeTab = 'simulator'"
                                class="toggle-btn relative z-10 flex-1 h-full flex items-center justify-center text-[15px] font-medium rounded-full transition-all duration-500 ease-[cubic-bezier(0.45,0,0.2,1)]"
                                :class="activeTab === 'simulator' ? 'text-blue-800 dark:text-blue-200 active' : 'text-gray-700 dark:text-gray-300'"
                            >
                                <span class="emoji-icon text-lg mr-1">💳</span>
                                <span class="btn-text hidden sm:inline">Simulador</span>
                            </button>
                        </div>
                    </div>

                    <!-- Descripción con transición -->
                    <div class="relative h-12 mb-4 flex items-center justify-center">
                        <p class="desc-text text-gray-700 dark:text-gray-300 text-center absolute inset-0 flex items-center justify-center" x-show="activeTab === 'fundamental'" :class="activeTab === 'fundamental' ? 'content-enter' : 'content-exit'">
                            Explora los principales modelos de cálculo financiero utilizados en el ámbito académico y profesional.
                        </p>
                        <p class="desc-text text-gray-700 dark:text-gray-300 text-center absolute inset-0 flex items-center justify-center" x-show="activeTab === 'advanced'" :class="activeTab === 'advanced' ? 'content-enter' : 'content-exit'" style="display: none;">
                            Herramientas especializadas para análisis financieros complejos y evaluación de proyectos.
                        </p>
                        <p class="desc-text text-gray-700 dark:text-gray-300 text-center absolute inset-0 flex items-center justify-center" x-show="activeTab === 'simulator'" :class="activeTab === 'simulator' ? 'content-enter' : 'content-exit'" style="display: none;">
                            Simula diferentes escenarios de crédito con nuestros cuatro modelos completos de simulación.
                        </p>
                    </div>

                    <!-- Grid Fundamentales -->
                    <div x-show="activeTab === 'fundamental'" :class="activeTab === 'fundamental' ? 'content-enter' : 'content-exit'">
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
                    <div x-show="activeTab === 'advanced'" :class="activeTab === 'advanced' ? 'content-enter' : 'content-exit'" style="display: none;">
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
                                                Sistemas para amortizar deudas mediante pagos periódicos.
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
                                                El estudio de la acumulación y reinversión de intereses.
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
                            <a href="{{ url('/tasa-interna-retorno') }}" class="group block focus:outline-none">
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

                    <!-- Grid Simulador de Créditos -->
                    <div x-show="activeTab === 'simulator'" :class="activeTab === 'simulator' ? 'content-enter' : 'content-exit'" style="display: none;">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Tarjeta principal del Simulador -->
                            <a href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}" class="group block focus:outline-none">
                                <div class="custom-hover rounded-xl shadow-md border-l-4 border-blue-500 p-6 relative overflow-hidden text-gray-800 dark:text-gray-200 transition-all duration-500 hover:shadow-xl hover:border-l-8 flex flex-col h-full">
                                    <div class="absolute inset-0 bg-blue-500/20 rounded-xl -z-10 transition-all duration-700 group-hover:bg-gradient-to-r group-hover:from-blue-400 group-hover:via-blue-600 group-hover:to-blue-500"></div>
                                    <div class="flex items-start relative z-10 transition-colors duration-500 group-hover:text-white flex-1">
                                        <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-lg mr-4 group-hover:bg-white/30 transition-colors duration-500 flex-shrink-0">
                                            <span class="text-4xl text-blue-500 group-hover:text-white transition-colors duration-500">💳</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold mb-3">Simulador de Créditos Completo</h3>
                                            <p class="mt-2 text-lg mb-4">
                                                Explora nuestros <strong>cuatro modelos de simulación de créditos</strong> diseñados para ayudarte a tomar decisiones financieras informadas.
                                            </p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">📐</span>
                                                    <div>
                                                        <h4 class="font-semibold">Modelo Gradientes</h4>
                                                        <p class="text-sm opacity-90">Pagos con incrementos constantes o porcentuales</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">📈</span>
                                                    <div>
                                                        <h4 class="font-semibold">Interés Simple</h4>
                                                        <p class="text-sm opacity-90">Cálculos sobre capital inicial únicamente</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">📊</span>
                                                    <div>
                                                        <h4 class="font-semibold">Interés Compuesto</h4>
                                                        <p class="text-sm opacity-90">Intereses sobre intereses acumulados</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">📋</span>
                                                    <div>
                                                        <h4 class="font-semibold">Sistemas de Amortización</h4>
                                                        <p class="text-sm opacity-90">Tablas de pago detalladas</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4 mx-auto items-center flex justify-center">
                                        <span class="difficulty-badge difficulty-intermediate">
                                            <span>🚀</span>
                                            <span>Cuatro Modelos Integrados</span>
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

                        <!-- Beneficios del Simulador -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center">
                                <div class="text-3xl mb-2">🎯</div>
                                <h4 class="font-semibold text-blue-700 dark:text-blue-300">Precisión</h4>
                                <p class="text-sm text-blue-600 dark:text-blue-400">Cálculos exactos basados en fórmulas financieras estándar</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                                <div class="text-3xl mb-2">⚡</div>
                                <h4 class="font-semibold text-green-700 dark:text-green-300">Rapidez</h4>
                                <p class="text-sm text-green-600 dark:text-green-400">Resultados instantáneos para múltiples escenarios</p>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            @push('scripts')
                <script>
                    function modelToggle() {
                        return {
                            activeTab: 'fundamental'
                        }
                    }
                </script>
            @endpush
        </div>

        <!-- Sidebar con información adicional - MEJORADO -->
        <div class="space-y-6">
            <!-- Consejos útiles - Actualizado -->
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
                    <div class="flex items-start">
                        <span class="flex-shrink-0 text-success-500 text-lg mr-2">•</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Usa nuestro simulador de créditos para comparar diferentes escenarios antes de tomar decisiones.</p>
                    </div>
                </div>
            </x-filament::section>

            <!-- Enlaces útiles - Actualizado -->
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
                    <a href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}" class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-semibold">
                        <span class="mr-2">🚀</span> Simulador de Créditos
                    </a>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.gestiopolis.com/calculadora-tir-tasa-interna-retorno/" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm">
                        <span class="mr-2">→</span> Guía para cálculo de TIR
                    </a>
                </div>
            </x-filament::section>

            <!-- Calculadora rápida - Actualizado -->
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
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">💳</span>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">Simulación de Créditos</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Compara diferentes opciones con nuestro simulador.</p>
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
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Simulador</span>
                        <div class="flex space-x-1">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

    </div>
    <x-filament::section class="mt-4 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700 rounded-xl">
        <p class="text-center text-lg text-primary-700 dark:text-primary-300 font-medium col-span-full">
            🎯 Esta herramienta ha sido desarrollada con fines educativos para apoyar el aprendizaje de conceptos financieros
            en el ámbito universitario. Incluye un <strong>simulador completo de créditos</strong> con cuatro modelos diferentes.
            Siempre verifica tus cálculos con múltiples fuentes.
        </p>
    </x-filament::section>
</x-filament-panels::page>
