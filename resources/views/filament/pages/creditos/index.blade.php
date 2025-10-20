<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Título principal --}}
        <x-sections.heading-title
            title="Gestor de Créditos"
            quote='"La información financiera bien organizada es la clave para tomar decisiones inteligentes sobre tu dinero." — Warren Buffett"'
            button-text="Nuevo Crédito"
            href="{{ \App\Filament\Pages\Creditos\CreateCredit::getUrl() }}"
        >
            <x-slot:icon>
                <x-heroicon-c-document-text class="size-16 text-white" aria-hidden="true"/>
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Main Table Section --}}
        <x-sections.content title="Listado de Créditos" class="p-0" :is-collapsible="true">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-table-cells class="w-5 h-5 text-primary-600 dark:text-primary-400"/>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Todos los Créditos
                                    Registrados</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Visualiza y gestiona todos
                                    los créditos del sistema</p>
                            </div>
                        </div>
                        <div
                            class="hidden lg:flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <x-heroicon-o-document-duplicate class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
                            <span
                                class="text-sm font-bold text-gray-900 dark:text-white">{{ \App\Models\Credit::count() }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">registros</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden">
                    {{ $this->table }}
                </div>
            </div>
        </x-sections.content>

        {{-- Statistics Cards - Profesional con colores --}}
        <x-sections.content title="Resumen General" :is-collapsible="true" class="p-0 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Credits Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow overflow-hidden group">
                    <div
                        class="absolute -right-8 -top-8 w-24 h-24 bg-primary-100 dark:bg-primary-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2.5 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                <x-heroicon-o-document-text class="w-5 h-5 text-primary-600 dark:text-primary-400"/>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">TOTAL</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Total de Créditos</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ \App\Models\Credit::count() }}
                        </p>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-500 dark:bg-primary-600" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                {{-- Calculated Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow overflow-hidden group">
                    <div
                        class="absolute -right-8 -top-8 w-24 h-24 bg-emerald-100 dark:bg-emerald-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400"/>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">LISTOS</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Calculados</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ \App\Models\Credit::where('status', 'calculated')->count() }}
                        </p>
                        @php
                            $totalCredits = \App\Models\Credit::count();
                            $calculatedCredits = \App\Models\Credit::where('status', 'calculated')->count();
                            $percentageCalculated = $totalCredits > 0 ? round(($calculatedCredits / $totalCredits) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 dark:bg-emerald-600 transition-all"
                                     style="width: {{ $percentageCalculated }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $percentageCalculated }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Pending Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow overflow-hidden group">
                    <div
                        class="absolute -right-8 -top-8 w-24 h-24 bg-amber-100 dark:bg-amber-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2.5 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                <x-heroicon-o-clock class="w-5 h-5 text-amber-600 dark:text-amber-400"/>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">PROCESO</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Pendientes</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ \App\Models\Credit::where('status', 'pending')->count() }}
                        </p>
                        @php
                            $pendingCredits = \App\Models\Credit::where('status', 'pending')->count();
                            $percentagePending = $totalCredits > 0 ? round(($pendingCredits / $totalCredits) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 dark:bg-amber-600 transition-all"
                                     style="width: {{ $percentagePending }}%"></div>
                            </div>
                            <span
                                class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $percentagePending }}%</span>
                        </div>
                    </div>
                </div>

                {{-- This Month Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow overflow-hidden group">
                    <div
                        class="absolute -right-8 -top-8 w-24 h-24 bg-violet-100 dark:bg-violet-900/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2.5 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                                <x-heroicon-o-calendar class="w-5 h-5 text-violet-600 dark:text-violet-400"/>
                            </div>
                            <span
                                class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ now()->format('M') }}</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Este Mes</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ \App\Models\Credit::whereMonth('created_at', now()->month)->count() }}
                        </p>
                        @php
                            $thisMonthCredits = \App\Models\Credit::whereMonth('created_at', now()->month)->count();
                            $percentageThisMonth = $totalCredits > 0 ? round(($thisMonthCredits / $totalCredits) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-violet-500 dark:bg-violet-600 transition-all"
                                     style="width: {{ $percentageThisMonth }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $percentageThisMonth }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Paid Status Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2.5 bg-sky-50 dark:bg-sky-900/20 rounded-lg">
                            <x-heroicon-o-banknotes class="w-5 h-5 text-sky-600 dark:text-sky-400"/>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Credit::where('status', 'paid')->count() }}
                        </p>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Créditos Pagados</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Completamente liquidados</p>
                    <div class="mt-3 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-sky-500 dark:bg-sky-600" style="width: 75%"></div>
                    </div>
                </div>

                {{-- Cancelled Status Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2.5 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                            <x-heroicon-o-x-circle class="w-5 h-5 text-rose-600 dark:text-rose-400"/>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Credit::where('status', 'cancelled')->count() }}
                        </p>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Créditos Cancelados</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Anulados o rechazados</p>
                    <div class="mt-3 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 dark:bg-rose-600" style="width: 15%"></div>
                    </div>
                </div>

            </div>
        </x-sections.content>

        {{-- Additional Statistics Row --}}

        {{-- Performance Chart Section --}}
        <x-sections.content title="Rendimiento del Sistema" class="p-0" collapsed="true" :is-collapsible="true">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Monthly Trend --}}
                        <div class="space-y-4">
                            <h5 class="text-sm font-bold text-gray-900 dark:text-white">Tendencia Mensual</h5>
                            <div class="space-y-3">
                                @for($i = 5; $i >= 0; $i--)
                                    @php
                                        $month = now()->subMonths($i);
                                        $count = \App\Models\Credit::whereYear('created_at', $month->year)
                                            ->whereMonth('created_at', $month->month)
                                            ->count();
                                        $maxCount = \App\Models\Credit::selectRaw('COUNT(*) as count')
                                            ->whereDate('created_at', '>=', now()->subMonths(6))
                                            ->groupByRaw('EXTRACT(MONTH FROM created_at), EXTRACT(YEAR FROM created_at)')
                                            ->orderByDesc('count')
                                            ->value('count') ?? 1;
                                        $percentage = ($count / max($maxCount, 1)) * 100;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span
                                                class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $month->format('M Y') }}</span>
                                            <span
                                                class="text-xs font-bold text-gray-900 dark:text-white">{{ $count }}</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden">
                                            <div class="h-full bg-primary-500 dark:bg-primary-600 transition-all"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Status Distribution --}}
                        <div class="space-y-4">
                            <h5 class="text-sm font-bold text-gray-900 dark:text-white">Distribución por Estado</h5>
                            <div class="space-y-3">
                                @php
                                    $statuses = [
                                        ['name' => 'Calculados', 'count' => \App\Models\Credit::where('status', 'calculated')->count(), 'color' => 'emerald'],
                                        ['name' => 'Pendientes', 'count' => \App\Models\Credit::where('status', 'pending')->count(), 'color' => 'amber'],
                                        ['name' => 'Pagados', 'count' => \App\Models\Credit::where('status', 'paid')->count(), 'color' => 'sky'],
                                        ['name' => 'Cancelados', 'count' => \App\Models\Credit::where('status', 'cancelled')->count(), 'color' => 'gray'],
                                    ];
                                @endphp
                                @foreach($statuses as $status)
                                    @php
                                        $statusPercentage = $totalCredits > 0 ? ($status['count'] / $totalCredits) * 100 : 0;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span
                                                class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $status['name'] }}</span>
                                            <span class="text-xs font-bold text-gray-900 dark:text-white">{{ number_format($statusPercentage, 1) }}%</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden">
                                            <div
                                                class="h-full bg-gradient-to-r from-{{ $status['color'] }}-500 to-{{ $status['color'] }}-600 dark:from-{{ $status['color'] }}-600 dark:to-{{ $status['color'] }}-700 transition-all"
                                                style="width: {{ $statusPercentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>


        {{-- Consejos y Estados --}}
        <x-sections.content title="Información y Estados" :is-collapsible="true" collapsed="true" class="p-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Tips Card --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-light-bulb class="w-5 h-5 text-primary-600 dark:text-primary-400"/>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Consejos de Uso</h4>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800/50">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex-shrink-0 mt-0.5">
                                    <x-heroicon-o-magnifying-glass class="w-4 h-4 text-blue-600 dark:text-blue-400"/>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Búsqueda
                                        Rápida</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Encuentra créditos
                                        instantáneamente usando código, nombre, apellido o cédula en la barra
                                        superior.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/50">
                                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex-shrink-0 mt-0.5">
                                    <x-heroicon-o-funnel class="w-4 h-4 text-emerald-600 dark:text-emerald-400"/>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Filtros
                                        Inteligentes</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Aplica múltiples filtros: tipo
                                        de cálculo, estado del crédito, usuario responsable o rango de fechas.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50">
                                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex-shrink-0 mt-0.5">
                                    <x-heroicon-o-clipboard-document
                                        class="w-4 h-4 text-amber-600 dark:text-amber-400"/>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Copiar
                                        Datos</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Haz clic en los íconos junto a
                                        códigos y cédulas para copiarlos automáticamente al portapapeles.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 p-4 rounded-lg bg-violet-50 dark:bg-violet-900/10 border border-violet-200 dark:border-violet-800/50">
                                <div class="p-2 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex-shrink-0 mt-0.5">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-violet-600 dark:text-violet-400"/>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Exportar
                                        Datos</h5>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Exporta la información a Excel o
                                        PDF para generar reportes personalizados.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Status Legend --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center gap-3">
                            <x-heroicon-o-tag class="w-5 h-5 text-primary-600 dark:text-primary-400"/>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Estados del Crédito</h4>
                        </div>
                    </div>
                    <div class="p-6 space-y-2">
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg bg-primary-50 dark:bg-primary-900/10 border border-primary-200 dark:border-primary-800/50">
                            <div class="relative">
                                <div class="w-3 h-3 rounded-full bg-primary-500 dark:bg-primary-600"></div>
                                <div
                                    class="absolute inset-0 w-3 h-3 rounded-full bg-primary-400 animate-pulse opacity-50"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-primary-900 dark:text-primary-100">Calculado</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Procesado correctamente</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50">
                            <div class="relative">
                                <div class="w-3 h-3 rounded-full bg-amber-500 dark:bg-amber-600"></div>
                                <div
                                    class="absolute inset-0 w-3 h-3 rounded-full bg-amber-400 animate-pulse opacity-50"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-amber-900 dark:text-amber-100">Pendiente</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">En espera de procesamiento</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/50">
                            <div class="relative">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 dark:bg-emerald-600"></div>
                                <div
                                    class="absolute inset-0 w-3 h-3 rounded-full bg-emerald-400 animate-pulse opacity-50"></div>
                             </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">Pagado</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Completamente liquidado</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/50">
                            <div class="w-3 h-3 rounded-full bg-red-400 dark:bg-red-600"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-red-900 dark:text-red-100">Cancelado</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Anulado o rechazado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <x-heroicon-o-calculator class="w-4 h-4 text-blue-600 dark:text-blue-400"/>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Activos</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Tipos de Cálculo</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Credit::distinct('type')->count('type') }}</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                            <x-heroicon-o-arrow-trending-up class="w-4 h-4 text-emerald-600 dark:text-emerald-400"/>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">+8%</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Tasa de Aprobación</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCredits > 0 ? round(($calculatedCredits / $totalCredits) * 100) : 0 }}
                        %</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                            <x-heroicon-o-user-group class="w-4 h-4 text-violet-600 dark:text-violet-400"/>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Asignados</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Usuarios Activos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Credit::whereNotNull('user_id')->distinct('user_id')->count('user_id') }}</p>
                </div>
            </div>
        </x-sections.content>
    </div>
</x-filament-panels::page>
